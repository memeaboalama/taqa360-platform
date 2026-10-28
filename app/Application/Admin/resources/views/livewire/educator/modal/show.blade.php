<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملف مزود الخدمة - د. أحمد محمد</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --background: #F2F2F3; /* #F2F2F3 */
            --foreground: #333333;   /* #333333 */
            --card: #FFFFFF;
            --primary: #306FAA;    /* #306FAA */
            --muted-foreground: #6b7280; /* gray-500 */
            --border: #e5e7eb; /* gray-200 */
            --radius: 0.5rem;
        }
        body {
            font-family: 'Cairo', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
        }
        .text-primary { color: var(--primary); }
        .bg-primary { background-color: var(--primary); }
        .text-foreground { color: var(--foreground); }
        .bg-background { background-color: var(--background); }
        .text-muted-foreground { color: var(--muted-foreground); }
        .bg-card { background-color: var(--card); }
        .border { border-color: var(--border); }
        .rounded-lg { border-radius: var(--radius); }
        .shadow-lg { box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
            font-weight: 600;
            transition: all 0.2s;
        }
        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #245C8E;
        }
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--border);
            color: var(--foreground);
        }
        .btn-outline:hover {
            background-color: #f9fafb; /* gray-50 */
        }
        .badge {
            display: inline-flex;
            align-items: center;
            border-radius: 9999px;
            border: 1px solid var(--border);
            padding: 2px 10px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        .badge-secondary {
            background-color: #f3f4f6; /* gray-100 */
        }
        .progress-bar {
            background-color: #e5e7eb; /* gray-200 */
            border-radius: 9999px;
            height: 0.5rem;
            overflow: hidden;
        }
        .progress-indicator {
             background-color: var(--primary);
             height: 100%;
        }
    </style>
</head>
<body class="antialiased">

    <div class="min-h-screen bg-background">
      <div class="relative">
        <div class="h-48 md:h-64 bg-gradient-to-r from-primary/20 to-primary/10 relative">
          <div class="absolute inset-0 bg-black/10"></div>
        </div>
        <div class="container mx-auto px-4">
          <div class="relative -mt-16 md:-mt-20">
            <div class="rounded-lg border bg-card text-foreground shadow-lg">
              <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6">
                  <div class="flex flex-col items-center md:items-start flex-shrink-0">
                    <div class="relative">
                      <div class="relative flex h-32 w-32 shrink-0 overflow-hidden rounded-full border-4 border-white shadow-lg">
                          <img class="aspect-square h-full w-full" src="https://ui-avatars.com/api/?name=أ&background=F8C421&color=333333&size=128" />
                      </div>
                      <div class="absolute bottom-1 right-1 h-6 w-6 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                  </div>
                  <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                      <div>
                        <h1 class="text-3xl font-bold text-foreground mb-1">د. أحمد محمد</h1>
                        <p class="text-xl text-muted-foreground mb-2">أستاذ في علوم الحاسوب</p>
                        <p class="text-lg text-primary font-medium mb-4">جامعة الملك سعود</p>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4 text-sm">
                          <div class="flex items-center gap-2">
                            <svg class="h-5 w-5 text-[#EA993E] fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                            <span class="font-semibold">4.9</span>
                            <span class="text-muted-foreground">(156)</span>
                          </div>
                          <div class="flex items-center gap-2 text-muted-foreground">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.663l.001.109m-11.962 0A5.625 5.625 0 015.625 6.375c1.619 0 3.097.663 4.125 1.75l.001.109m-4.126 0A23.848 23.848 0 0012 3.75c2.331 0 4.512.645 6.374 1.766l.001.109-4.126 3.75z" /></svg>
                            <span>89 مشروع</span>
                          </div>
                          <div class="flex items-center gap-2 text-muted-foreground">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>خلال ساعة</span>
                          </div>
                          <div class="flex items-center gap-2 text-muted-foreground">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            <span>الرياض، السعودية</span>
                          </div>
                        </div>
                        <div class="badge badge-secondary">الذكاء الاصطناعي وتعلم الآلة</div>
                      </div>
                      <div class="flex flex-col gap-3 min-w-[200px] w-full md:w-auto">
                          <button class="btn btn-primary w-full p-3 gap-2">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            تواصل الآن
                          </button>
                          <div class="flex gap-2">
                            <button class="btn btn-outline p-2 flex-1"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg></button>
                            <button class="btn btn-outline p-2 flex-1"><svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.186 2.25 2.25 0 00-3.933 2.186z" /></svg></button>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="container mx-auto px-4 py-8" x-data="{ activeTab: 'overview' }">
        <div class="bg-gray-100 rounded-md p-1 inline-flex items-center justify-center text-gray-500 w-full grid grid-cols-2 sm:grid-cols-5 mb-6">
            <button @click="activeTab = 'overview'" :class="{'bg-white text-foreground shadow-sm': activeTab === 'overview'}" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium transition-all">نظرة عامة</button>
            <button @click="activeTab = 'services'" :class="{'bg-white text-foreground shadow-sm': activeTab === 'services'}" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium transition-all">الخدمات</button>
            <button @click="activeTab = 'portfolio'" :class="{'bg-white text-foreground shadow-sm': activeTab === 'portfolio'}" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium transition-all">الأعمال</button>
            <button @click="activeTab = 'achievements'" :class="{'bg-white text-foreground shadow-sm': activeTab === 'achievements'}" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium transition-all">الإنجازات</button>
            <button @click="activeTab = 'reviews'" :class="{'bg-white text-foreground shadow-sm': activeTab === 'reviews'}" class="inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium transition-all">التقييمات</button>
        </div>

        <div class="mt-2">
          <div x-show="activeTab === 'overview'" x-cloak>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <div class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm"><div class="flex flex-col space-y-1.5 p-6"><h3 class="text-2xl font-semibold leading-none tracking-tight flex items-center gap-2"><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>النبذة الشخصية</h3></div><div class="p-6 pt-0"><p class="text-muted-foreground leading-relaxed">دكتوراه في علوم الحاسوب من جامعة ستانفورد، خبرة أكثر من 15 عامًا في مجال الذكاء الاصطناعي وتعلم الآلة. نشرت أكثر من 50 بحثًا علميًا في مؤتمرات دولية محكمة.</p></div></div>
                <div class="rounded-lg border bg-card ..."><div class="p-6 pt-0"><div class="space-y-4">{{-- Languages --}}</div></div></div>
              </div>
              <div class="space-y-6">
                <div class="rounded-lg border bg-card ..."><div class="p-6 pt-0 space-y-4">{{-- Quick Stats --}}</div></div>
              </div>
            </div>
          </div>
          <div x-show="activeTab === 'services'" x-cloak>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">{{-- Services Cards --}}</div>
          </div>
        </div>
      </div>
    </div>

</body>
</html>
