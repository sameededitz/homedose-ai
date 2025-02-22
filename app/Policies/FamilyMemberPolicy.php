<?php

namespace App\Policies;

use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FamilyMemberPolicy
{
    /**
     * Determine whether the user can view any family members.
     */
    public function viewAny(): bool
    {
        return true; // Any authenticated user can view their own family members
    }

    /**
     * Determine whether the user can view a specific family member.
     */
    public function view(User $user, FamilyMember $familyMember): Response
    {
        return $user->id === $familyMember->user_id
            ? Response::allow()
            : Response::deny('You do not own this family member.', 403);
    }

    /**
     * Determine whether the user can create a family member.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create family members
    }

    /**
     * Determine whether the user can update a family member.
     */
    public function update(User $user, FamilyMember $familyMember): Response
    {
        return $user->id === $familyMember->user_id
            ? Response::allow()
            : Response::deny('You do not own this family member.', 403);
    }

    /**
     * Determine whether the user can delete a family member.
     */
    public function delete(User $user, FamilyMember $familyMember): Response
    {
        return $user->id === $familyMember->user_id
            ? Response::allow()
            : Response::deny('You do not own this family member.', 403);
    }
}
