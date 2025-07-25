# User Management System Installation

This guide will help you add the user management system to your IELTS Mock Platform.

## Installation Steps

1. **Run the migration** to add ban fields to the users table:
   ```bash
   php artisan migrate
   ```

2. **Clear application cache**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

3. **Access the User Management System**:
   - Login as an admin
   - Navigate to the Admin Panel
   - Click on "All Users" in the sidebar under "User Management" section

## Features Added

### User Management Features:
- **User List View**: View all users with search and filter options
- **Filter by Role**: Filter users by Student, Teacher, or Admin role
- **Filter by Status**: Filter by Active or Banned status
- **Search**: Search users by name, email, or phone number
- **User Details**: View comprehensive user information including:
  - Basic info (name, email, phone)
  - Subscription details
  - Test attempts history
  - Activity logs
  - Teacher information (if applicable)
  - Referral information

### User Actions:
- **Create New User**: Add new users with specific roles
- **Edit User**: Update user information and role
- **Ban/Unban Users**: Ban users with a reason or unban them
- **Verify Email**: Manually verify user emails
- **Impersonate User**: Login as any user (except admins) for support
- **Delete User**: Remove users from the system

### Security Features:
- Banned users are automatically logged out
- Cannot delete or impersonate your own account
- Cannot impersonate other admin accounts
- Ban reason is tracked and displayed

## Usage

### Banning a User:
1. Go to the user list
2. Click the three-dot menu next to the user
3. Select "Ban User"
4. Enter a ban reason
5. Click "Confirm Ban"

### Impersonating a User:
1. Find the user you want to impersonate
2. Click "Login as User" from the action menu
3. You'll be logged in as that user
4. A yellow button appears at the bottom right to stop impersonating

### Creating a New User:
1. Click "Add New User" button
2. Fill in the required information
3. Select the user role (Student, Teacher, or Admin)
4. Optionally mark email as verified
5. Click "Create User"

## Notes
- The ban functionality prevents banned users from logging in
- All user activities are logged for security
- The system tracks who created/modified users
- Email verification can be done manually by admins
