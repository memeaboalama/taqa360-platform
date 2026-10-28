<?php

return [
    'order_status' => [
        // 'draft' => 'لم يتم تحضيره',
        'draft' => 'مسودة',
        'pending' => 'قيد الانتظار',
        'current' => 'جاري التجهيز',
        'confirmed' => 'مؤكد',
        'completed' => 'تم التوصيل',
        'expired' => 'منتهي صلاحية',
        'cancelled' => 'تم الغاء الطلب',
        'on_way' => 'في الطريق',
        'ready' => 'جاهز',
        'will-be-late' => 'متأخر',
        'accepted' => 'Accepted',
        'timeout' => 'Timeout',
    ],
    'order_mark' => [
        'yellow' => 'أصفر',
        'red' => 'أحمر ',
        'green' => 'أخضر',
        'blue' => 'أزرق',
        'white' => 'أبيض',
    ],

    'academic_degree' => [
        'diploma' => 'دبلوم',
        'high_school' => 'ثانوي',
        'bachelor' => 'بكالوريوس',
        'master' => 'ماجستير',
        'doctorate' => 'دكتوراه',
        'phd' => 'دكتوراه',
    ],
    'project' => [
        'initial_task_for' => 'المهمة الأولية لـ :title',
    ],
    'educator_status' => [
        'active' => 'نشط',
        'inactive' => 'غير نشط',
        'pending' => 'قيد المراجعة',
        'rejected' => 'مرفوض',
        'suspended' => 'موقوف',
        'banned' => 'محظور',
    ],
    'document_status' => [
        'pending' => 'قيد المراجعة',
        'verified' => 'تم التحقق',
        'rejected' => 'مرفوض',
    ],
    'educational_request' => [
        'status' => [
            'draft' => 'مسودة',
            'pending' => 'قيد المراجعة',
            'approved' => 'مقبول',
            'assigned' => 'تم التعيين',
            'rejected' => 'مرفوض',
            'in_progress' => 'جاري التنفيذ',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ],
        'type' => [
            'general' => 'عامة',
            'task' => 'واجب / مهمة',
            'project' => 'مشروع / مشروع تخرج',
            'research' => 'بحث أكاديمي',
            'explanation' => 'شرح وتوضيح / دروس خصوصية',
            'hourly' => 'بالساعة',
            'fixed' => 'مبلغ ثابت',
        ],
    ],
];
