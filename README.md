# Humanify Admin Panel

The **Humanify Admin Panel** is a lightweight and efficient administrative interface designed for managing user accounts and premium subscriptions. This tool is ideal for platforms requiring straightforward user and subscription management without unnecessary complexity.

---

## Features

### 👥 **User Management**
- **Add Users**: Manually create new user accounts.
- **Edit Users**: Update user details like name, email, or status.
- **Deactivate/Activate Users**: Manage user access with a single toggle.
- **Search & Filter**: Quickly locate users with advanced filtering options.

### 💎 **Premium Management**
- **Manage Subscriptions**: Assign or revoke premium status for users.
- **Subscription Status**: View and update the premium status of users.
- **Track Premium Users**: Filter and monitor premium-only users.

---

## Installation

### Prerequisites
- PHP 8.2+
- Laravel 11
- MySQL
- Composer

### Steps
1. **Clone the repository**:
    ```bash
    git clone https://github.com/sameededitz/humanify.git
    ```
2. **Navigate to the project directory**:
    ```bash
    cd humanify-admin-panel
    ```
3. **Install dependencies**:
    ```bash
    composer install
    ```
4. **Set up environment variables**:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
5. **Run migrations and seeders**:
    ```bash
    php artisan migrate --seed
    ```
6. **Start the server**:
    ```bash
    php artisan serve
    ```

---

## Usage

### Admin Features
- **User List**: View a detailed list of all registered users.
- **Manage Premium**: Grant or revoke premium access for specific users.
- **Search and Sort**: Efficiently manage large user bases with search and sorting tools.

---

## Technologies Used
- **Backend**: Laravel 11
- **Frontend**: Livewire 3, Bootstrap
- **Database**: MySQL

---

## Developer Information
- **Developer**: Sameed
- **Instagram**: [@not_sameed52](https://www.instagram.com/not_sameed52/)
- **Discord**: sameededitz
- **Linktree**: [linktr.ee/sameededitz](https://linktr.ee/sameededitz)
- **Company**: TecClubb
  - **Website**: [https://tecclubb.com/](https://tecclubb.com/)
  - **Contact**: tecclubb@gmail.com

---

## Contributing
Contributions are welcome! Fork the repository, create a new branch, and submit a pull request. Open an issue first for major changes.

---

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## Contact
For inquiries or support, reach out via:
- **Email**: tecclubb@gmail.com
- **Website**: [https://tecclubb.com/](https://tecclubb.com/)
