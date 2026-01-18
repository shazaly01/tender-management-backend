<?php

namespace App\Policies;

use App\Models\Owner;
use App\Models\User;

class OwnerPolicy
{
    /**
     * هل يمكن للمستخدم عرض قائمة الملاك؟
     */
    public function viewAny(User $user): bool
    {
        return $user->can('owner.view');
    }

    /**
     * هل يمكن للمستخدم عرض مالك محدد؟
     */
    public function view(User $user, Owner $owner): bool
    {
        return $user->can('owner.view');
    }

    /**
     * هل يمكن للمستخدم إنشاء مالك جديد؟
     */
    public function create(User $user): bool
    {
        return $user->can('owner.create');
    }

    /**
     * هل يمكن للمستخدم تعديل بيانات مالك؟
     */
    public function update(User $user, Owner $owner): bool
    {
        return $user->can('owner.update');
    }

    /**
     * هل يمكن للمستخدم حذف مالك؟
     */
    public function delete(User $user, Owner $owner): bool
    {
        return $user->can('owner.delete');
    }

    /**
     * هل يمكن للمستخدم استعادة مالك محذوف (Soft Delete)؟
     * عادةً نربطها بصلاحية الحذف أو التحديث. هنا سأربطها بالحذف.
     */
    public function restore(User $user, Owner $owner): bool
    {
        return $user->can('owner.delete');
    }

    /**
     * الحذف النهائي (Force Delete)
     */
    public function forceDelete(User $user, Owner $owner): bool
    {
        return $user->can('owner.delete');
    }
}
