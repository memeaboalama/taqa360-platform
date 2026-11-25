(() => {
    const form = document.querySelector('.merchant-spec-filters');
    if (!form) return;
    const dropdowns = [];
    form.querySelectorAll('[data-spec-dropdown]').forEach(root => {
        const trigger = root.querySelector('[data-spec-trigger]');
        const menu = root.querySelector('[data-spec-menu]');
        const search = root.querySelector('[data-spec-search]');
        const list = root.querySelector('[data-spec-options]');
        const status = root.querySelector('[data-spec-status]');
        const retry = root.querySelector('[data-spec-retry]');
        const inputs = root.querySelector('[data-selected-inputs]');
        const summary = root.querySelector('[data-selection-label]');
        const selected = new Map([...inputs.querySelectorAll('input')].map(input => [input.value, input.dataset.selectedLabel]));
        const options = new Map();
        let page = 1, hasMore = true, loading = false, initialized = false, failed = false;
        let controller, revision = 0, timer;

        function updateSelection() {
            inputs.replaceChildren();
            selected.forEach((label, value) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `specs[${root.dataset.field}][]`;
                input.value = value;
                input.dataset.selectedLabel = label;
                inputs.append(input);
            });
            const labels = [...selected.values()];
            summary.textContent = labels.length ? labels.slice(0, 2).join('، ') + (labels.length > 2 ? ` (+${labels.length - 2})` : '') : form.dataset.placeholder;
            trigger.title = labels.join('، ');
            options.forEach((button, value) => {
                button.setAttribute('aria-selected', String(selected.has(value)));
                button.querySelector('.spec-check').textContent = selected.has(value) ? '✓' : '';
            });
        }

        function appendOption(option) {
            const value = String(option.value);
            if (options.has(value)) return;
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'merchant-spec-option';
            button.setAttribute('role', 'option');
            button.setAttribute('aria-selected', String(selected.has(value)));
            const check = document.createElement('span');
            check.className = 'spec-check';
            check.setAttribute('aria-hidden', 'true');
            check.textContent = selected.has(value) ? '✓' : '';
            const label = document.createElement('span');
            label.className = 'spec-option-label';
            label.textContent = option.label;
            const count = document.createElement('small');
            count.textContent = option.count;
            button.append(check, label, count);
            button.addEventListener('click', () => {
                selected.has(value) ? selected.delete(value) : selected.set(value, option.label);
                updateSelection();
            });
            list.append(button);
            options.set(value, button);
        }

        async function load() {
            if (loading || !hasMore) return;
            loading = true;
            failed = false;
            initialized = true;
            retry.hidden = true;
            status.textContent = form.dataset.loading;
            list.setAttribute('aria-busy', 'true');
            controller = new AbortController();
            const currentRevision = revision;
            const url = new URL(root.dataset.url, location.href);
            url.searchParams.set('field', root.dataset.field);
            url.searchParams.set('search', search.value.trim());
            url.searchParams.set('page', page);
            try {
                const response = await fetch(url, {signal: controller.signal, headers: {'Accept': 'application/json'}});
                if (!response.ok) throw new Error('Request failed');
                const data = await response.json();
                if (currentRevision !== revision) return;
                if (!Array.isArray(data.results)) throw new Error('Invalid options');
                data.results.forEach(appendOption);
                hasMore = data.has_more === true;
                page++;
                status.textContent = options.size ? '' : form.dataset.empty;
            } catch (error) {
                if (currentRevision !== revision || error.name === 'AbortError') return;
                failed = true;
                status.textContent = form.dataset.error;
                retry.hidden = false;
            } finally {
                if (currentRevision === revision) {
                    loading = false;
                    list.setAttribute('aria-busy', 'false');
                }
            }
        }

        function close() {
            menu.hidden = true;
            trigger.setAttribute('aria-expanded', 'false');
            search.setAttribute('aria-expanded', 'false');
        }
        function open() {
            dropdowns.forEach(dropdown => dropdown.close());
            menu.hidden = false;
            trigger.setAttribute('aria-expanded', 'true');
            search.setAttribute('aria-expanded', 'true');
            search.focus();
            if (!initialized) load();
        }
        trigger.addEventListener('click', () => menu.hidden ? open() : close());
        trigger.addEventListener('keydown', event => {
            if (event.key === 'ArrowDown') { event.preventDefault(); open(); }
        });
        search.addEventListener('input', () => {
            clearTimeout(timer);
            controller?.abort();
            revision++;
            page = 1;
            loading = false;
            hasMore = true;
            failed = false;
            options.clear();
            list.replaceChildren();
            list.scrollTop = 0;
            retry.hidden = true;
            status.textContent = form.dataset.loading;
            timer = setTimeout(load, 250);
        });
        list.addEventListener('scroll', () => {
            if (!failed && list.scrollHeight - list.scrollTop - list.clientHeight < 60) load();
        });
        retry.addEventListener('click', load);
        root.addEventListener('keydown', event => {
            if (event.key === 'Escape' && !menu.hidden) {
                event.preventDefault(); close(); trigger.focus(); return;
            }
            if (event.key === 'Enter' && event.target === search) {
                event.preventDefault(); return;
            }
            if (!['ArrowDown', 'ArrowUp'].includes(event.key) || menu.hidden || event.target === trigger) return;
            event.preventDefault();
            const buttons = [...options.values()];
            if (!buttons.length) return;
            const index = buttons.indexOf(document.activeElement);
            const next = index + (event.key === 'ArrowDown' ? 1 : -1);
            if (next < 0) search.focus();
            else buttons[Math.min(next, buttons.length - 1)].focus();
        });
        root.addEventListener('focusout', event => {
            if (!root.contains(event.relatedTarget)) close();
        });
        updateSelection();
        dropdowns.push({root, close});
    });
    document.addEventListener('click', event => {
        dropdowns.forEach(dropdown => { if (!dropdown.root.contains(event.target)) dropdown.close(); });
    });
})();
