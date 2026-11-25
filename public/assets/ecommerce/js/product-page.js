(() => {
    const page = document.querySelector('.product-page-refresh');
    if (!page) return;
    const mainImage = page.querySelector('#product-main-image');
    const thumbnails = [...page.querySelectorAll('[data-product-image]')];
    thumbnails.forEach(button => button.addEventListener('click', () => {
        mainImage.src = button.dataset.productImage;
        thumbnails.forEach(thumbnail => {
            const selected = thumbnail === button;
            thumbnail.classList.toggle('active', selected);
            thumbnail.setAttribute('aria-pressed', String(selected));
        });
    }));

    const tabs = [...page.querySelectorAll('[data-product-tab]')];
    const selectTab = tab => {
        tabs.forEach(button => {
            const selected = button === tab;
            button.classList.toggle('active', selected);
            button.setAttribute('aria-selected', String(selected));
            button.tabIndex = selected ? 0 : -1;
            page.querySelector(`#${button.getAttribute('aria-controls')}`).hidden = !selected;
        });
    };
    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => selectTab(tab));
        tab.addEventListener('keydown', event => {
            const rtl = getComputedStyle(page).direction === 'rtl';
            let next;
            if (event.key === 'Home') next = 0;
            if (event.key === 'End') next = tabs.length - 1;
            if (event.key === 'ArrowRight') next = (index + (rtl ? -1 : 1) + tabs.length) % tabs.length;
            if (event.key === 'ArrowLeft') next = (index + (rtl ? 1 : -1) + tabs.length) % tabs.length;
            if (next === undefined) return;
            event.preventDefault();
            selectTab(tabs[next]);
            tabs[next].focus();
        });
    });

    const search = page.querySelector('[data-spec-search]');
    const normalize = value => value.normalize('NFKD').replace(/[\u0300-\u036f\u064b-\u065f\u0670]/g, '').toLocaleLowerCase().trim();
    search?.addEventListener('input', () => {
        const query = normalize(search.value);
        let matches = 0;
        page.querySelectorAll('[data-spec-group]').forEach(group => {
            let groupMatches = 0;
            group.querySelectorAll('[data-spec-row]').forEach(row => {
                row.hidden = !normalize(row.dataset.specSearchText).includes(query);
                if (!row.hidden) groupMatches++;
            });
            group.hidden = groupMatches === 0;
            matches += groupMatches;
        });
        page.querySelector('[data-spec-no-matches]').hidden = !query || matches > 0;
    });
})();
