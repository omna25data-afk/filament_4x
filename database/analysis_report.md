# تقرير تحليل قاعدة البيانات all_database_db

تاريخ التحليل: 2025-10-31 01:34:17

## ملخص عام

- عدد الجداول: 61
- عدد العلاقات: 64
- إجمالي السجلات: 7,830

## تحليل الجداول

### جدول: `administrative_units`

**الوصف:** جدول الوحدات الإدارية

**عدد السجلات:** 4

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للوحدة الإدارية |
| `name_ar` | varchar(255) | NO |  | NULL |  | الاسم باللغة العربية |
| `name_en` | varchar(255) | YES |  | NULL |  | الاسم باللغة الإنجليزية |
| `unit_type` | enum('governorate','directorate','sub_district','village','locality') | NO |  | NULL |  | نوع الوحدة (محافظة، مديرية، إلخ) |
| `parent_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف الوحدة الأصل (للتسلسل الهرمي) |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة تفعيل الوحدة |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `parent_id` → `administrative_units`.`id`

---

### جدول: `agency_contracts`

**الوصف:** جدول بيانات عقود الوكالات التفصيلية

**عدد السجلات:** 808

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لعقد الوكالة |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرتبط |
| `agency_subtype` | enum('new_agency','cancellation_agency') | NO |  | NULL |  | النوع الفرعي للوكالة |
| `principal_name` | varchar(255) | NO |  | NULL |  | اسم الموكل |
| `agent_name` | varchar(255) | NO |  | NULL |  | اسم الوكيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `entry_id` → `entries`.`id`

---

### جدول: `archives`

**الوصف:** جدول الأرشيف

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للأرشيف |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المؤرشف |
| `archived_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي قام بالأرشفة |
| `archived_at` | timestamp | YES |  | NULL |  | تاريخ الأرشفة |
| `notes` | text | YES |  | NULL |  | ملاحظات الأرشفة |

**المفاتيح الخارجية:**

- `archived_by_user_id` → `users`.`id`
- `entry_id` → `entries`.`id`

---

### جدول: `assignment_registers`

**الوصف:** جدول سجلات التكاليف

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل التكليف |
| `assigned_notary_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف الأمين المكلف |
| `original_notary_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف الأمين الأصلي |
| `reason` | text | NO |  | NULL |  | سبب التكليف |
| `start_hijri_date` | date | NO |  | NULL |  | تاريخ البداية بالهجري |
| `end_hijri_date` | date | YES |  | NULL |  | تاريخ النهاية بالهجري |
| `assigned_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي قام بالتكليف |
| `status` | enum('active','completed','cancelled') | NO |  | active |  | حالة التكليف |
| `notes` | text | YES |  | NULL |  | ملاحظات |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `assigned_by_user_id` → `users`.`id`
- `assigned_notary_id` → `notaries`.`id`
- `original_notary_id` → `notaries`.`id`

---

### جدول: `blacklist`

**الوصف:** جدول القائمة السوداء

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للسجل |
| `person_name_ar` | varchar(255) | NO |  | NULL |  | اسم الشخص باللغة العربية |
| `identity_type` | varchar(255) | YES |  | NULL |  | نوع الهوية |
| `identity_number` | varchar(255) | YES |  | NULL |  | رقم الهوية |
| `reason` | text | NO |  | NULL |  | سبب الإدراج في القائمة السوداء |
| `added_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي أضاف السجل |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `added_by_user_id` → `users`.`id`

---

### جدول: `cache`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 2

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `key` | varchar(255) | NO | PRI | NULL |  | *لا يوجد وصف* |
| `value` | mediumtext | NO |  | NULL |  | *لا يوجد وصف* |
| `expiration` | int(11) | NO |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `cache_locks`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `key` | varchar(255) | NO | PRI | NULL |  | *لا يوجد وصف* |
| `owner` | varchar(255) | NO |  | NULL |  | *لا يوجد وصف* |
| `expiration` | int(11) | NO |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `calculators`

**الوصف:** جدول الحاسبات

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للحاسبة |
| `name_ar` | varchar(255) | NO |  | NULL |  | اسم الحاسبة باللغة العربية |
| `description_ar` | text | YES |  | NULL |  | وصف الحاسبة باللغة العربية |
| `type` | enum('inheritance','tax','zakat','area','age','date_conversion','weight_conversion','distance_conversion') | NO |  | NULL |  | نوع الحاسبة |
| `config_json` | text | YES |  | NULL |  | إعدادات الحاسبة بتنسيق JSON |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

---

### جدول: `circulars_decisions`

**الوصف:** جدول التعاميم والقرارات

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للتعاميم والقرارات |
| `title_ar` | varchar(255) | NO |  | NULL |  | العنوان باللغة العربية |
| `description` | text | YES |  | NULL |  | الوصف |
| `file_path` | varchar(255) | NO |  | NULL |  | مسار الملف |
| `uploaded_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي رفع الملف |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `uploaded_by_user_id` → `users`.`id`

---

### جدول: `complaint_registers`

**الوصف:** جدول سجلات الشكاوى

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل الشكوى |
| `complainant_name_ar` | varchar(255) | NO |  | NULL |  | اسم المشتكي باللغة العربية |
| `complainant_contact` | varchar(255) | YES |  | NULL |  | بيانات التواصل مع المشتكي |
| `complaint_against_notary_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف الأمين المشتكى عليه |
| `complaint_hijri_date` | date | NO |  | NULL |  | تاريخ الشكوى بالهجري |
| `complaint_details` | text | NO |  | NULL |  | تفاصيل الشكوى |
| `status` | enum('pending','under_investigation','resolved','dismissed') | NO |  | pending |  | حالة الشكوى |
| `handled_by_user_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف المستخدم الذي يتعامل مع الشكوى |
| `resolution_notes` | text | YES |  | NULL |  | ملاحظات الحل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `complaint_against_notary_id` → `notaries`.`id`
- `handled_by_user_id` → `users`.`id`

---

### جدول: `contract_types`

**الوصف:** جدول أنواع العقود والقيود

**عدد السجلات:** 9

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لنوع العقد |
| `parent_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف النوع الأصل (للتسلسل الهرمي) |
| `name_ar` | varchar(255) | NO |  | NULL |  | الاسم باللغة العربية |
| `name_en` | varchar(255) | YES |  | NULL |  | الاسم باللغة الإنجليزية |
| `description` | text | YES |  | NULL |  | وصف العقد |
| `level` | enum('main','sub','sub_sub') | NO |  | main |  | مستوى العقد (رئيسي، فرعي، فرعي ثانوي) |
| `is_system_defined` | tinyint(1) | NO |  | 0 |  | هل النوع محدد من قبل النظام |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة تفعيل النوع |
| `display_order` | int(10) unsigned | NO |  | 0 |  | ترتيب العرض |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `parent_id` → `contract_types`.`id`

---

### جدول: `custom_register_entries`

**الوصف:** جدول بيانات السجلات المخصصة

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للبيانات المخصصة |
| `custom_register_type_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف نوع السجل المخصص |
| `entry_data_json` | longtext | NO |  | NULL |  | بيانات السجل بتنسيق JSON |
| `created_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي أنشأ السجل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `created_by_user_id` → `users`.`id`
- `custom_register_type_id` → `custom_register_types`.`id`

---

### جدول: `custom_register_fields`

**الوصف:** جدول حقول السجلات المخصصة

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للحقل المخصص |
| `custom_register_type_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف نوع السجل المخصص |
| `name_ar` | varchar(255) | NO |  | NULL |  | اسم الحقل باللغة العربية |
| `key` | varchar(255) | NO |  | NULL |  | مفتاح الحقل |
| `type` | enum('text','number','date','select','textarea','file','checkbox') | NO |  | NULL |  | نوع الحقل |
| `options` | longtext | YES |  | NULL |  | خيارات الحقل |
| `is_required` | tinyint(1) | NO |  | 0 |  | هل الحقل مطلوب |
| `display_order` | int(10) unsigned | NO |  | 0 |  | ترتيب العرض |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `custom_register_type_id` → `custom_register_types`.`id`

---

### جدول: `custom_register_types`

**الوصف:** جدول السجلات المخصصة

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لنوع السجل المخصص |
| `name_ar` | varchar(255) | NO |  | NULL |  | اسم السجل باللغة العربية |
| `description` | text | YES |  | NULL |  | وصف السجل |
| `icon_class` | varchar(255) | YES |  | NULL |  | فئة الأيقونة |
| `color_code` | varchar(7) | YES |  | NULL |  | كود اللون |
| `created_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي أنشأ السجل |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `created_by_user_id` → `users`.`id`

---

### جدول: `disposal_contracts`

**الوصف:** جدول بيانات عقود التصرفات التفصيلية

**عدد السجلات:** 109

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لعقد التصرف |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرتبط |
| `disposal_subtype_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف نوع التصرف الفرعي |
| `disposer_name` | varchar(255) | NO |  | NULL |  | اسم المتصرف |
| `disposer_for_name` | varchar(255) | NO |  | NULL |  | اسم المتصرف له |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `disposal_subtype_id` → `contract_types`.`id`
- `entry_id` → `entries`.`id`

---

### جدول: `divorce_attestations`

**الوصف:** جدول بيانات إشهادات الطلاق التفصيلية

**عدد السجلات:** 52

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لإشهادة الطلاق |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرتبط |
| `husband_name` | varchar(255) | NO |  | NULL |  | اسم الزوج |
| `wife_name` | varchar(255) | NO |  | NULL |  | اسم الزوجة |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `entry_id` → `entries`.`id`

---

### جدول: `documentation_templates`

**الوصف:** جدول نماذج التوثيق

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لنموذج التوثيق |
| `name_ar` | varchar(255) | NO |  | NULL |  | اسم النموذج باللغة العربية |
| `category_ar` | enum('register_cover','box_spine','label','memo','statistical_form','report_template') | NO |  | NULL |  | فئة النموذج |
| `file_path` | varchar(255) | NO |  | NULL |  | مسار الملف |
| `uploaded_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي رفع النموذج |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `uploaded_by_user_id` → `users`.`id`

---

### جدول: `dynamic_form_data`

**الوصف:** جدول بيانات الحقول الديناميكية

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للبيانات الديناميكية |
| `entry_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف القيد |
| `dynamic_form_field_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف الحقل الديناميكي |
| `value_text` | text | YES |  | NULL |  | القيمة النصية |
| `value_number` | decimal(12,2) | YES |  | NULL |  | القيمة الرقمية |
| `value_date` | date | YES |  | NULL |  | القيمة التاريخية |
| `value_json` | longtext | YES |  | NULL |  | القيمة بتنسيق JSON |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `dynamic_form_field_id` → `dynamic_form_fields`.`id`
- `entry_id` → `entries`.`id`

---

### جدول: `dynamic_form_fields`

**الوصف:** جدول الحقول الديناميكية للنماذج

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للحقل الديناميكي |
| `contract_type_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف نوع العقد |
| `name_ar` | varchar(255) | NO |  | NULL |  | اسم الحقل باللغة العربية |
| `key` | varchar(255) | NO |  | NULL |  | مفتاح الحقل |
| `type` | enum('text','number','email','date','textarea','select','checkbox','radio','file') | NO |  | NULL |  | نوع الحقل |
| `options` | longtext | YES |  | NULL |  | خيارات الحقل (JSON) لـ select, checkbox, radio |
| `is_required` | tinyint(1) | NO |  | 0 |  | هل الحقل مطلوب |
| `display_order` | int(10) unsigned | NO |  | 0 |  | ترتيب العرض |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `contract_type_id` → `contract_types`.`id`

---

### جدول: `entries`

**الوصف:** جدول القيود العامة (الجدول المركزي)

**عدد السجلات:** 1,946

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للقيد |
| `register_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف السجل التابع له القيد |
| `contract_type_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف نوع العقد |
| `writer_type_id` | smallint(5) unsigned | NO |  | NULL |  | معرّف نوع الكاتب |
| `writer_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي أدخل القيد |
| `writer_notary_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف الأمين إذا كان الكاتب أميناً |
| `writer_other_id` | bigint(20) unsigned | YES |  | NULL |  | معرّف الكاتب إذا كان من "الآخرون" |
| `document_hijri_date` | date | NO |  | NULL |  | تاريخ الوثيقة بالهجري |
| `document_gregorian_date` | date | YES |  | NULL | STORED GENERATED | تاريخ الوثيقة بالميلادي (محسوب) |
| `document_paper_number` | varchar(255) | YES |  | NULL |  | رقم الوثيقة المدون على الورقة |
| `entry_status` | enum('draft','pending_certification','certified','delivered_to_concerned','rejected') | NO | MUL | draft |  | حالة القيد |
| `certifier_user_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف المستخدم الذي قام بالتوثيق |
| `certification_hijri_date` | date | YES |  | NULL |  | تاريخ التوثيق بالهجري |
| `certification_gregorian_date` | date | YES |  | NULL |  | تاريخ التوثيق بالميلادي |
| `court_register_entry_number` | varchar(255) | YES |  | NULL |  | رقم القيد في سجل المحكمة |
| `court_register_page_number` | int(10) unsigned | YES |  | NULL |  | رقم الصفحة في سجل المحكمة |
| `court_register_number` | int(10) unsigned | YES |  | NULL |  | رقم سجل المحكمة |
| `court_box_number` | varchar(255) | YES |  | NULL |  | رقم الصندوق في المحكمة |
| `delivery_hijri_date` | date | YES |  | NULL |  | تاريخ التسليم بالهجري |
| `delivery_gregorian_date` | date | YES |  | NULL |  | تاريخ التسليم بالميلادي |
| `delivery_receipt_image_path` | varchar(255) | YES |  | NULL |  | مسار صورة إيصال التسليم |
| `notes` | text | YES |  | NULL |  | ملاحظات |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `certifier_user_id` → `users`.`id`
- `contract_type_id` → `contract_types`.`id`
- `register_id` → `registers`.`id`
- `writer_notary_id` → `notaries`.`id`
- `writer_user_id` → `users`.`id`

---

### جدول: `entry_financial_data`

**الوصف:** جدول البيانات المالية للقيود

**عدد السجلات:** 1,946

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للبيانات المالية |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرتبط |
| `base_fees_amount` | decimal(10,2) | NO |  | NULL |  | مبلغ الرسوم الأساسية |
| `local_revenue_amount` | decimal(10,2) | YES |  | NULL | STORED GENERATED | مبلغ الإيرادات المحلية (محسوب) |
| `revenue_incentive_amount` | decimal(10,2) | YES |  | NULL | STORED GENERATED | مبلغ حافز الإيرادات (محسوب) |
| `court_preparation_amount` | decimal(10,2) | YES |  | NULL | STORED GENERATED | مبلغ تجهيز المحكمة (محسوب) |
| `support_amount` | decimal(10,2) | YES |  | NULL | STORED GENERATED | مبلغ الدعم (محسوب) |
| `judiciary_support_amount` | decimal(10,2) | YES |  | NULL | STORED GENERATED | مبلغ دعم القضاء (محسوب) |
| `documentation_development_incentive_amount` | decimal(10,2) | YES |  | NULL | STORED GENERATED | مبلغ حافز تطوير التوثيق (محسوب) |
| `sustainability_fee_amount` | decimal(10,2) | NO |  | 200.00 |  | مبلغ رسوم الاستدامة |
| `fine_amount` | decimal(10,2) | NO |  | 0.00 |  | مبلغ الغرامة |
| `tax_amount` | decimal(10,2) | NO |  | 0.00 |  | مبلغ الضريبة |
| `zakat_amount` | decimal(10,2) | NO |  | 0.00 |  | مبلغ الزكاة |
| `total_collected_amount` | decimal(10,2) | YES |  | NULL | STORED GENERATED | المبلغ الإجمالي المحصل (محسوب) |
| `fees_voucher_number` | varchar(255) | YES |  | NULL |  | رقم إيصال الرسوم |
| `fees_voucher_date` | date | YES |  | NULL |  | تاريخ إيصال الرسوم |
| `tax_voucher_number` | varchar(255) | YES |  | NULL |  | رقم إيصال الضريبة |
| `tax_voucher_date` | date | YES |  | NULL |  | تاريخ إيصال الضريبة |
| `zakat_voucher_number` | varchar(255) | YES |  | NULL |  | رقم إيصال الزكاة |
| `zakat_voucher_date` | date | YES |  | NULL |  | تاريخ إيصال الزكاة |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `entry_id` → `entries`.`id`

---

### جدول: `evaluation_registers`

**الوصف:** جدول سجلات التقييم

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل التقييم |
| `notary_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف الأمين المقيم |
| `evaluation_period_ar` | varchar(255) | NO |  | NULL |  | فترة التقييم باللغة العربية |
| `evaluation_hijri_date` | date | NO |  | NULL |  | تاريخ التقييم بالهجري |
| `performance_score` | tinyint(3) unsigned | YES |  | NULL |  | درجة الأداء (مثلاً من 1 إلى 5) |
| `evaluation_notes` | text | NO |  | NULL |  | ملاحظات التقييم |
| `evaluated_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي قام بالتقييم |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `evaluated_by_user_id` → `users`.`id`
- `notary_id` → `notaries`.`id`

---

### جدول: `external_request_registers`

**الوصف:** جدول سجلات قيد الطلبات الخارجية

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل الطلب الخارجي |
| `request_type_ar` | varchar(255) | NO |  | NULL |  | نوع الطلب باللغة العربية |
| `requester_name_ar` | varchar(255) | NO |  | NULL |  | اسم طالب الخدمة باللغة العربية |
| `requester_contact` | varchar(255) | YES |  | NULL |  | بيانات التواصل مع طالب الخدمة |
| `requester_identity_type` | varchar(255) | YES |  | NULL |  | نوع هوية طالب الخدمة |
| `requester_identity_number` | varchar(255) | YES |  | NULL |  | رقم هوية طالب الخدمة |
| `related_entry_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف القيد المرتبط |
| `request_hijri_date` | date | NO |  | NULL |  | تاريخ الطلب بالهجري |
| `status` | enum('pending','approved','rejected','fulfilled') | NO |  | pending |  | حالة الطلب |
| `processed_by_user_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف المستخدم الذي عالج الطلب |
| `processed_hijri_date` | date | YES |  | NULL |  | تاريخ المعالجة بالهجري |
| `notes` | text | YES |  | NULL |  | ملاحظات |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `processed_by_user_id` → `users`.`id`
- `related_entry_id` → `entries`.`id`

---

### جدول: `failed_jobs`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | *لا يوجد وصف* |
| `uuid` | varchar(255) | NO | UNI | NULL |  | *لا يوجد وصف* |
| `connection` | text | NO |  | NULL |  | *لا يوجد وصف* |
| `queue` | text | NO |  | NULL |  | *لا يوجد وصف* |
| `payload` | longtext | NO |  | NULL |  | *لا يوجد وصف* |
| `exception` | longtext | NO |  | NULL |  | *لا يوجد وصف* |
| `failed_at` | timestamp | NO |  | current_timestamp() |  | *لا يوجد وصف* |

---

### جدول: `fee_settings`

**الوصف:** جدول إعدادات الرسوم

**عدد السجلات:** 2

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لإعدادات الرسوم |
| `contract_type_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف نوع العقد |
| `calculation_type` | enum('fixed_amount','percentage_of_value','fixed_plus_percentage') | NO |  | NULL |  | نوع الحساب |
| `fixed_value` | decimal(10,2) | YES |  | NULL |  | المبلغ الثابت |
| `percentage_value` | decimal(5,2) | YES |  | NULL |  | النسبة المئوية |
| `max_cap` | decimal(10,2) | YES |  | NULL |  | الحد الأقصى للرسوم إذا كانت نسبة مئوية |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `contract_type_id` → `contract_types`.`id`

---

### جدول: `fine_settings`

**الوصف:** جدول إعدادات الغرامات

**عدد السجلات:** 2

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لإعدادات الغرامات |
| `contract_type_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف نوع العقد |
| `delay_start_days` | int(10) unsigned | NO |  | NULL |  | عدد أيام بدء التأخير |
| `delay_end_days` | int(10) unsigned | YES |  | NULL |  | عدد أيام نهاية التأخير (NULL يعني لا نهاية) |
| `calculation_type` | enum('fixed_amount','percentage_of_base_fees') | NO |  | NULL |  | نوع حساب الغرامة |
| `value` | decimal(10,2) | NO |  | NULL |  | المبلغ الثابت أو النسبة المئوية |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `contract_type_id` → `contract_types`.`id`

---

### جدول: `incoming_registers`

**الوصف:** جدول سجل الوارد

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل الوارد |
| `document_type_ar` | varchar(255) | NO |  | NULL |  | نوع الوثيقة باللغة العربية |
| `sender_ar` | varchar(255) | NO |  | NULL |  | المرسل باللغة العربية |
| `subject_ar` | varchar(255) | NO |  | NULL |  | الموضوع باللغة العربية |
| `incoming_hijri_date` | date | NO |  | NULL |  | تاريخ الوارد بالهجري |
| `received_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي استلم الوثيقة |
| `notes` | text | YES |  | NULL |  | ملاحظات |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `received_by_user_id` → `users`.`id`

---

### جدول: `job_batches`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | varchar(255) | NO | PRI | NULL |  | *لا يوجد وصف* |
| `name` | varchar(255) | NO |  | NULL |  | *لا يوجد وصف* |
| `total_jobs` | int(11) | NO |  | NULL |  | *لا يوجد وصف* |
| `pending_jobs` | int(11) | NO |  | NULL |  | *لا يوجد وصف* |
| `failed_jobs` | int(11) | NO |  | NULL |  | *لا يوجد وصف* |
| `failed_job_ids` | longtext | NO |  | NULL |  | *لا يوجد وصف* |
| `options` | mediumtext | YES |  | NULL |  | *لا يوجد وصف* |
| `cancelled_at` | int(11) | YES |  | NULL |  | *لا يوجد وصف* |
| `created_at` | int(11) | NO |  | NULL |  | *لا يوجد وصف* |
| `finished_at` | int(11) | YES |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `jobs`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | *لا يوجد وصف* |
| `queue` | varchar(255) | NO | MUL | NULL |  | *لا يوجد وصف* |
| `payload` | longtext | NO |  | NULL |  | *لا يوجد وصف* |
| `attempts` | tinyint(3) unsigned | NO |  | NULL |  | *لا يوجد وصف* |
| `reserved_at` | int(10) unsigned | YES |  | NULL |  | *لا يوجد وصف* |
| `available_at` | int(10) unsigned | NO |  | NULL |  | *لا يوجد وصف* |
| `created_at` | int(10) unsigned | NO |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `marriage_contracts`

**الوصف:** جدول بيانات عقود الزواج التفصيلية

**عدد السجلات:** 769

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لعقد الزواج |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرتبط |
| `husband_name` | varchar(255) | NO |  | NULL |  | اسم الزوج |
| `wife_name` | varchar(255) | NO |  | NULL |  | اسم الزوجة |
| `wife_age_at_marriage` | int(10) unsigned | YES |  | NULL |  | عمر الزوجة عند الزواج |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `entry_id` → `entries`.`id`

---

### جدول: `migrations`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 5

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | int(10) unsigned | NO | PRI | NULL | auto_increment | *لا يوجد وصف* |
| `migration` | varchar(255) | NO |  | NULL |  | *لا يوجد وصف* |
| `batch` | int(11) | NO |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `movement_registers`

**الوصف:** جدول سجلات الانتقال

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل الانتقال |
| `requester_name_ar` | varchar(255) | NO |  | NULL |  | اسم طالب الانتقال باللغة العربية |
| `requester_identity_type` | varchar(255) | YES |  | NULL |  | نوع هوية طالب الانتقال |
| `requester_identity_number` | varchar(255) | YES |  | NULL |  | رقم هوية طالب الانتقال |
| `requester_identity_issue_date` | date | YES |  | NULL |  | تاريخ إصدار الهوية |
| `requester_identity_issuing_authority` | varchar(255) | YES |  | NULL |  | جهة إصدار الهوية |
| `requester_status` | varchar(255) | YES |  | NULL |  | حالة طالب الانتقال |
| `movement_reason` | text | NO |  | NULL |  | سبب الانتقال |
| `movement_hijri_date` | date | YES |  | NULL |  | تاريخ الانتقال بالهجري |
| `movement_gregorian_date` | date | YES |  | NULL |  | تاريخ الانتقال بالميلادي |
| `assigned_notary_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف الأمين المخصص |
| `assigned_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي قام بالتخصيص |
| `status` | enum('pending','completed','cancelled') | NO |  | pending |  | حالة السجل |
| `notes` | text | YES |  | NULL |  | ملاحظات |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `assigned_by_user_id` → `users`.`id`
- `assigned_notary_id` → `notaries`.`id`

---

### جدول: `notaries`

**الوصف:** جدول بيانات الأمناء الشرعيين

**عدد السجلات:** 1

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للأمين |
| `user_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف المستخدم المرتبط بالأمين |
| `first_name_ar` | varchar(255) | NO |  | NULL |  | الاسم الأول |
| `second_name_ar` | varchar(255) | NO |  | NULL |  | اسم الأب |
| `third_name_ar` | varchar(255) | NO |  | NULL |  | اسم الجد |
| `fourth_name_ar` | varchar(255) | NO |  | NULL |  | اللقب |
| `birth_place_governorate_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف محافظة الميلاد |
| `birth_place_directorate_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف مديرية الميلاد |
| `birth_place_sub_district_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف عزلة الميلاد |
| `birth_place_village_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف قرية الميلاد |
| `birth_date` | date | YES |  | NULL |  | تاريخ الميلاد |
| `home_phone` | varchar(255) | YES |  | NULL |  | رقم الهاتف المنزلي |
| `address` | text | YES |  | NULL |  | العنوان |
| `qualification` | varchar(255) | YES |  | NULL |  | المؤهل العلمي |
| `job` | varchar(255) | YES |  | NULL |  | الوظيفة |
| `workplace` | varchar(255) | YES |  | NULL |  | مكان العمل |
| `functional_status` | enum('active','inactive','suspended') | NO |  | active |  | الحالة الوظيفية |
| `stop_reason` | text | YES |  | NULL |  | سبب التوقف عن العمل |
| `stop_date` | date | YES |  | NULL |  | تاريخ التوقف عن العمل |
| `notes` | text | YES |  | NULL |  | ملاحظات إضافية |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |
| `م` | int(11) | YES |  | NULL |  | *لا يوجد وصف* |
| `الاسم الأول` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `الإسم الثاني` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `الإسم الثالث` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `الإسم الرابع` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `اللقب` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `محل الميلاد` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `تاريخ الميلاد` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `نوع الهوية` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `رقم الهوية` | bigint(20) | YES |  | NULL |  | *لا يوجد وصف* |
| `تاريخ الإصدار` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `جهة الإصدار` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `العمل` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `جهة العمل` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `المؤهل` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `العنوان` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `قرى مناطق الإختصاص` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `عزلة مناطق الإختصاص` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `رقم التلفون` | int(11) | YES |  | NULL |  | *لا يوجد وصف* |
| `رقم القرار الوزاري` | int(11) | YES |  | NULL |  | *لا يوجد وصف* |
| `تاريخ القرار` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `رقم بطاقة الترخيص` | int(11) | YES |  | NULL |  | *لا يوجد وصف* |
| `تاريخ الترخيص` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `تاريخ إنتهاء أول ترخيص` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `رقم البطاقة الإلكترونية` | int(11) | YES |  | NULL |  | *لا يوجد وصف* |
| `تاريخ إصدار البطاقة` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `تاريخ إنتهاء أول بطاقة` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |

**المفاتيح الخارجية:**

- `birth_place_directorate_id` → `administrative_units`.`id`
- `birth_place_governorate_id` → `administrative_units`.`id`
- `birth_place_sub_district_id` → `administrative_units`.`id`
- `birth_place_village_id` → `administrative_units`.`id`
- `user_id` → `users`.`id`

---

### جدول: `notifications`

**الوصف:** جدول الإشعارات

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للإشعار |
| `user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم المرسل له الإشعار |
| `type` | enum('info','success','warning','danger') | NO |  | NULL |  | نوع الإشعار |
| `title` | varchar(255) | NO |  | NULL |  | عنوان الإشعار |
| `message` | text | NO |  | NULL |  | نص الإشعار |
| `is_read` | tinyint(1) | NO |  | 0 |  | حالة القراءة |
| `related_entry_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف القيد المرتبط |
| `related_notary_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف الأمين المرتبط |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `related_entry_id` → `entries`.`id`
- `related_notary_id` → `notaries`.`id`
- `user_id` → `users`.`id`

---

### جدول: `other_writers`

**الوصف:** جدول الكتاب الآخرين

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للكاتب الآخر |
| `name_ar` | varchar(100) | NO |  | NULL |  | الاسم باللغة العربية |
| `notes` | text | YES |  | NULL |  | ملاحظات |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

---

### جدول: `outgoing_registers`

**الوصف:** جدول سجل الصادر

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل الصادر |
| `document_type_ar` | varchar(255) | NO |  | NULL |  | نوع الوثيقة باللغة العربية |
| `recipient_ar` | varchar(255) | NO |  | NULL |  | المستلم باللغة العربية |
| `subject_ar` | varchar(255) | NO |  | NULL |  | الموضوع باللغة العربية |
| `outgoing_hijri_date` | date | NO |  | NULL |  | تاريخ الصادر بالهجري |
| `sent_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي أرسل الوثيقة |
| `notes` | text | YES |  | NULL |  | ملاحظات |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `sent_by_user_id` → `users`.`id`

---

### جدول: `partition_contracts`

**الوصف:** جدول بيانات عقود القسمة التفصيلية

**عدد السجلات:** 24

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لعقد القسمة |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرتبط |
| `deceased_name` | varchar(255) | NO |  | NULL |  | اسم المتوفى |
| `heirs_details` | text | NO |  | NULL |  | تفاصيل الورثة |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `entry_id` → `entries`.`id`

---

### جدول: `password_reset_tokens`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `email` | varchar(255) | NO | PRI | NULL |  | *لا يوجد وصف* |
| `token` | varchar(255) | NO |  | NULL |  | *لا يوجد وصف* |
| `created_at` | timestamp | YES |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `reconciliation_attestations`

**الوصف:** جدول بيانات إشهادات الرجعة التفصيلية

**عدد السجلات:** 1

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لإشهادة الرجعة |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرتبط |
| `husband_name` | varchar(255) | NO |  | NULL |  | اسم الزوج |
| `wife_name` | varchar(255) | NO |  | NULL |  | اسم الزوجة |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `entry_id` → `entries`.`id`

---

### جدول: `register_types`

**الوصف:** جدول أنواع السجلات

**عدد السجلات:** 3

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لنوع السجل |
| `name_ar` | varchar(255) | NO | UNI | NULL |  | الاسم باللغة العربية |
| `name_en` | varchar(255) | YES |  | NULL |  | الاسم باللغة الإنجليزية |
| `description` | text | YES |  | NULL |  | وصف نوع السجل |
| `icon_class` | varchar(255) | YES |  | NULL |  | فئة الأيقونة للعرض في الواجهة |
| `color_code` | varchar(7) | YES |  | NULL |  | كود اللون للعرض في الواجهة |
| `is_system_defined` | tinyint(1) | NO |  | 0 |  | هل النوع محدد من قبل النظام أم قابل للتعديل |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة تفعيل النوع |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

---

### جدول: `registers`

**الوصف:** جدول السجلات

**عدد السجلات:** 1

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للسجل |
| `register_type_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف نوع السجل |
| `name` | varchar(255) | NO |  | NULL |  | اسم السجل |
| `number` | int(10) unsigned | NO |  | NULL |  | رقم السجل |
| `hijri_year` | int(10) unsigned | NO |  | NULL |  | السنة الهجرية للسجل |
| `gregorian_year` | int(10) unsigned | YES |  | NULL | STORED GENERATED | السنة الميلادية (محسوبة تلقائياً) |
| `page_count` | int(10) unsigned | NO |  | NULL |  | عدد صفحات السجل |
| `entries_per_page` | int(10) unsigned | NO |  | 1 |  | عدد القيود في كل صفحة |
| `first_entry_serial_in_register` | int(10) unsigned | NO |  | 1 |  | الرقم التسلسلي لأول قيد في السجل |
| `last_entry_serial_in_register` | int(10) unsigned | YES |  | NULL |  | الرقم التسلسلي لآخر قيد في السجل |
| `assigned_notary_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف الأمين المخصص للسجل |
| `owner_type` | enum('admin','notary') | NO | MUL | NULL |  | نوع المالك (رئيس القلم أو أمين) |
| `owner_id` | bigint(20) unsigned | NO |  | NULL |  | معرّف المالك (يرتبط بـ users أو notaries حسب النوع) |
| `opening_minutes_date` | date | YES |  | NULL |  | تاريخ محضر الافتتاح |
| `closing_minutes_date` | date | YES |  | NULL |  | تاريخ محضر الإغلاق |
| `status` | enum('active','completed','archived') | NO |  | active |  | حالة السجل |
| `created_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي أنشأ السجل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `assigned_notary_id` → `notaries`.`id`
- `created_by_user_id` → `users`.`id`
- `register_type_id` → `register_types`.`id`

---

### جدول: `rejection_certification_registers`

**الوصف:** جدول سجلات رفض توثيق المحرر

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل رفض التوثيق |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرفوض |
| `rejection_reason` | text | NO |  | NULL |  | سبب الرفض |
| `rejected_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي رفض التوثيق |
| `rejection_hijri_date` | date | NO |  | NULL |  | تاريخ الرفض بالهجري |
| `notes` | text | YES |  | NULL |  | ملاحظات |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

**المفاتيح الخارجية:**

- `entry_id` → `entries`.`id`
- `rejected_by_user_id` → `users`.`id`

---

### جدول: `sale_contracts`

**الوصف:** جدول بيانات عقود البيع التفصيلية

**عدد السجلات:** 183

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لعقد البيع |
| `entry_id` | bigint(20) unsigned | NO | UNI | NULL |  | معرّف القيد المرتبط |
| `sale_subtype` | enum('movable','immovable') | NO |  | NULL |  | نوع البيع (منقول أو غير منقول) |
| `seller_name` | varchar(255) | NO |  | NULL |  | اسم البائع |
| `buyer_name` | varchar(255) | NO |  | NULL |  | اسم المشتري |
| `item_description` | text | NO |  | NULL |  | وصف المبيع |
| `item_value` | decimal(12,2) | NO |  | NULL |  | قيمة المبيع |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `entry_id` → `entries`.`id`

---

### جدول: `saved_reports`

**الوصف:** جدول التقارير المحفوظة

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للتقرير المحفوظ |
| `user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي حفظ التقرير |
| `name_ar` | varchar(255) | NO |  | NULL |  | اسم التقرير باللغة العربية |
| `config_json` | text | NO |  | NULL |  | إعدادات التقرير (الفلاتر, الأعمدة, إلخ) |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `user_id` → `users`.`id`

---

### جدول: `sessions`

**الوصف:** جدول جلسات المستخدمين

**عدد السجلات:** 9

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | varchar(255) | NO | PRI | NULL |  | معرف الجلسة |
| `user_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرف المستخدم |
| `ip_address` | varchar(45) | YES |  | NULL |  | عنوان IP |
| `user_agent` | text | YES |  | NULL |  | معلومات المتصفح |
| `payload` | longtext | NO |  | NULL |  | بيانات الجلسة |
| `last_activity` | int(11) | NO | MUL | NULL |  | آخر نشاط |

**المفاتيح الخارجية:**

- `user_id` → `users`.`id`

---

### جدول: `system_logs`

**الوصف:** جدول سجلات النظام

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل النظام |
| `user_id` | bigint(20) unsigned | YES | MUL | NULL |  | معرّف المستخدم الذي قام بالعملية |
| `action` | varchar(255) | NO |  | NULL |  | نوع العملية |
| `details` | longtext | NO |  | NULL |  | تفاصيل العملية بتنسيق JSON |
| `ip_address` | varchar(45) | YES |  | NULL |  | عنوان IP |
| `user_agent` | text | YES |  | NULL |  | معلومات المتصفح |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ العملية |

**المفاتيح الخارجية:**

- `user_id` → `users`.`id`

---

### جدول: `system_settings`

**الوصف:** جدول إعدادات النظام

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `key` | varchar(100) | NO | PRI | NULL |  | مفتاح الإعداد |
| `value` | text | YES |  | NULL |  | قيمة الإعداد |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

---

### جدول: `task_assignments`

**الوصف:** جدول تخصيص المهام للأمناء

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للتخصيص |
| `task_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المهمة |
| `assigned_notary_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف الأمين المخصص |
| `assigned_at` | timestamp | YES |  | NULL |  | تاريخ التخصيص |
| `status_at_assignment` | enum('pending','in_progress','completed','cancelled') | NO |  | pending |  | حالة التخصيص |
| `completed_by_notary_at` | date | YES |  | NULL |  | تاريخ إنجاز الأمين |
| `completion_notes` | text | YES |  | NULL |  | ملاحظات الإنجاز |

**المفاتيح الخارجية:**

- `assigned_notary_id` → `notaries`.`id`
- `task_id` → `tasks`.`id`

---

### جدول: `tasks`

**الوصف:** جدول المهام

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للمهمة |
| `creator_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي أنشأ المهمة |
| `title` | varchar(255) | NO |  | NULL |  | عنوان المهمة |
| `description` | text | YES |  | NULL |  | وصف المهمة |
| `status` | enum('pending','in_progress','completed','cancelled') | NO |  | pending |  | حالة المهمة |
| `due_date` | date | YES |  | NULL |  | تاريخ الاستحقاق |
| `completed_at` | date | YES |  | NULL |  | تاريخ الإنجاز |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `creator_user_id` → `users`.`id`

---

### جدول: `ui_field_settings`

**الوصف:** جدول إعدادات حقول الواجهة

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لإعدادات الحقل |
| `model_name` | varchar(100) | NO |  | NULL |  | اسم النموذج |
| `field_name` | varchar(100) | NO |  | NULL |  | اسم الحقل في النموذج |
| `context` | varchar(50) | NO |  | NULL |  | سياق العرض |
| `is_visible` | tinyint(1) | NO |  | 1 |  | هل الحقل مرئي |
| `label_ar` | varchar(255) | YES |  | NULL |  | التسمية باللغة العربية |
| `display_order` | int(10) unsigned | YES |  | NULL |  | ترتيب العرض |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

---

### جدول: `ui_themes`

**الوصف:** جدول أنماط الواجهة

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | int(10) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لنمط الواجهة |
| `name_ar` | varchar(100) | NO | UNI | NULL |  | اسم النمط باللغة العربية |
| `css_file_path` | varchar(255) | NO |  | NULL |  | مسار ملف CSS |
| `is_active` | tinyint(1) | NO |  | 0 |  | حالة التفعيل |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

---

### جدول: `users`

**الوصف:** جدول المستخدمين

**عدد السجلات:** 1

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد للمستخدم |
| `username` | varchar(255) | NO | UNI | NULL |  | اسم المستخدم (قد يكون رقم الهوية) |
| `password` | varchar(255) | NO |  | NULL |  | كلمة المرور المشفرة |
| `role` | enum('admin','notary','assistant_admin') | NO |  | notary |  | دور المستخدم في النظام |
| `full_name_ar` | varchar(255) | NO |  | NULL |  | الاسم الكامل باللغة العربية |
| `email` | varchar(255) | YES | UNI | NULL |  | البريد الإلكتروني (اختياري) |
| `phone_number` | varchar(255) | YES | UNI | NULL |  | رقم الهاتف (اختياري) |
| `profile_picture_path` | varchar(255) | YES |  | NULL |  | مسار صورة الملف الشخصي |
| `is_active` | tinyint(1) | NO |  | 1 |  | حالة تفعيل الحساب |
| `last_login_at` | timestamp | YES |  | NULL |  | تاريخ ووقت آخر تسجيل دخول |
| `email_verified_at` | timestamp | YES |  | NULL |  | تاريخ التحقق من البريد الإلكتروني |
| `remember_token` | varchar(100) | YES |  | NULL |  | رمز تذكر الدخول |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

---

### جدول: `violation_registers`

**الوصف:** جدول سجلات المخالفات

**عدد السجلات:** 0

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | bigint(20) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لسجل المخالفة |
| `notary_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف الأمين المخالف |
| `violation_hijri_date` | date | NO |  | NULL |  | تاريخ المخالفة بالهجري |
| `violation_type_ar` | varchar(255) | NO |  | NULL |  | نوع المخالفة باللغة العربية |
| `violation_details` | text | NO |  | NULL |  | تفاصيل المخالفة |
| `recorded_by_user_id` | bigint(20) unsigned | NO | MUL | NULL |  | معرّف المستخدم الذي سجل المخالفة |
| `action_taken` | text | YES |  | NULL |  | الإجراء المتخذ |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |
| `updated_at` | timestamp | YES |  | NULL |  | تاريخ آخر تحديث |

**المفاتيح الخارجية:**

- `notary_id` → `notaries`.`id`
- `recorded_by_user_id` → `users`.`id`

---

### جدول: `writer_types`

**الوصف:** جدول أنواع الكتاب

**عدد السجلات:** 4

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `id` | smallint(5) unsigned | NO | PRI | NULL | auto_increment | المعرف الفريد لنوع الكاتب |
| `name_ar` | varchar(50) | NO | UNI | NULL |  | الاسم باللغة العربية |
| `created_at` | timestamp | YES |  | NULL |  | تاريخ الإنشاء |

---

### جدول: `قيود_التصرفات`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 110

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `status` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sustainability_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `support_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `fees_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_hijri_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sub_box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `document_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `other_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `disposal_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `disposer_for_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `disposer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `contract_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `entry_id` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `قيود_الرجعة`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 2

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `status` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sustainability_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `support_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `fees_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_hijri_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sub_box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `document_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `other_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `wife_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `husband_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `contract_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `entry_id` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `قيود_الزواج`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 766

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `status` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sustainability_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `support_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `fees_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_hijri_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sub_box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `document_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `other_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `wife_age` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `wife_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `husband_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `contract_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `entry_id` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `قيود_الطلاق`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 53

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `status` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sustainability_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `support_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `fees_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_hijri_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sub_box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `document_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `other_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `divorce_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `wife_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `husband_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `contract_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `entry_id` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `قيود_القسمة`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 25

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `status` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sustainability_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `support_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `fees_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_hijri_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sub_box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `document_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `other_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `heirs_details` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `deceased_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `contract_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `entry_id` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `قيود_المبيع`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 184

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `status` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `zakat_voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `zakat_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `tax_voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `tax_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `item_description` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `location_area` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `item_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sustainability_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `support_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `fees_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_hijri_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sub_box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `document_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `other_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `item_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `buyer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `seller_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `contract_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `entry_id` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |

---

### جدول: `قيود_الوكالات`

**الوصف:** *لا يوجد وصف توضيحي*

**عدد السجلات:** 809

**الحقول:**

| اسم الحقل | النوع | فارغ | المفتاح | الافتراضي | إضافي | الوصف |
|-----------|-------|-------|---------|-----------|-------|-------|
| `status` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `voucher_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sustainability_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `support_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `fees_amount` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `certification_hijri_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `sub_box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `box_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_register_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_page_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `court_entry_number` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `document_date` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `other_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `notary_writer_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `agent_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `principal_name` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `contract_type` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |
| `entry_id` | varchar(255) | YES |  | NULL |  | *لا يوجد وصف* |

---

## العلاقات بين الجداول

- `administrative_units`.`parent_id` → `administrative_units`.`id`
- `agency_contracts`.`entry_id` → `entries`.`id`
- `archives`.`archived_by_user_id` → `users`.`id`
- `archives`.`entry_id` → `entries`.`id`
- `assignment_registers`.`assigned_by_user_id` → `users`.`id`
- `assignment_registers`.`assigned_notary_id` → `notaries`.`id`
- `assignment_registers`.`original_notary_id` → `notaries`.`id`
- `blacklist`.`added_by_user_id` → `users`.`id`
- `circulars_decisions`.`uploaded_by_user_id` → `users`.`id`
- `complaint_registers`.`complaint_against_notary_id` → `notaries`.`id`
- `complaint_registers`.`handled_by_user_id` → `users`.`id`
- `contract_types`.`parent_id` → `contract_types`.`id`
- `custom_register_entries`.`created_by_user_id` → `users`.`id`
- `custom_register_entries`.`custom_register_type_id` → `custom_register_types`.`id`
- `custom_register_fields`.`custom_register_type_id` → `custom_register_types`.`id`
- `custom_register_types`.`created_by_user_id` → `users`.`id`
- `disposal_contracts`.`disposal_subtype_id` → `contract_types`.`id`
- `disposal_contracts`.`entry_id` → `entries`.`id`
- `divorce_attestations`.`entry_id` → `entries`.`id`
- `documentation_templates`.`uploaded_by_user_id` → `users`.`id`
- `dynamic_form_data`.`dynamic_form_field_id` → `dynamic_form_fields`.`id`
- `dynamic_form_data`.`entry_id` → `entries`.`id`
- `dynamic_form_fields`.`contract_type_id` → `contract_types`.`id`
- `entries`.`certifier_user_id` → `users`.`id`
- `entries`.`contract_type_id` → `contract_types`.`id`
- `entries`.`register_id` → `registers`.`id`
- `entries`.`writer_notary_id` → `notaries`.`id`
- `entries`.`writer_user_id` → `users`.`id`
- `entry_financial_data`.`entry_id` → `entries`.`id`
- `evaluation_registers`.`evaluated_by_user_id` → `users`.`id`
- `evaluation_registers`.`notary_id` → `notaries`.`id`
- `external_request_registers`.`processed_by_user_id` → `users`.`id`
- `external_request_registers`.`related_entry_id` → `entries`.`id`
- `fee_settings`.`contract_type_id` → `contract_types`.`id`
- `fine_settings`.`contract_type_id` → `contract_types`.`id`
- `incoming_registers`.`received_by_user_id` → `users`.`id`
- `marriage_contracts`.`entry_id` → `entries`.`id`
- `movement_registers`.`assigned_by_user_id` → `users`.`id`
- `movement_registers`.`assigned_notary_id` → `notaries`.`id`
- `notaries`.`birth_place_directorate_id` → `administrative_units`.`id`
- `notaries`.`birth_place_governorate_id` → `administrative_units`.`id`
- `notaries`.`birth_place_sub_district_id` → `administrative_units`.`id`
- `notaries`.`birth_place_village_id` → `administrative_units`.`id`
- `notaries`.`user_id` → `users`.`id`
- `notifications`.`related_entry_id` → `entries`.`id`
- `notifications`.`related_notary_id` → `notaries`.`id`
- `notifications`.`user_id` → `users`.`id`
- `outgoing_registers`.`sent_by_user_id` → `users`.`id`
- `partition_contracts`.`entry_id` → `entries`.`id`
- `reconciliation_attestations`.`entry_id` → `entries`.`id`
- `registers`.`assigned_notary_id` → `notaries`.`id`
- `registers`.`created_by_user_id` → `users`.`id`
- `registers`.`register_type_id` → `register_types`.`id`
- `rejection_certification_registers`.`entry_id` → `entries`.`id`
- `rejection_certification_registers`.`rejected_by_user_id` → `users`.`id`
- `sale_contracts`.`entry_id` → `entries`.`id`
- `saved_reports`.`user_id` → `users`.`id`
- `sessions`.`user_id` → `users`.`id`
- `system_logs`.`user_id` → `users`.`id`
- `task_assignments`.`assigned_notary_id` → `notaries`.`id`
- `task_assignments`.`task_id` → `tasks`.`id`
- `tasks`.`creator_user_id` → `users`.`id`
- `violation_registers`.`notary_id` → `notaries`.`id`
- `violation_registers`.`recorded_by_user_id` → `users`.`id`
