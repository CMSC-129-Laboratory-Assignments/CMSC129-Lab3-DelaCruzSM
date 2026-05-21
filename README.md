# The Journal

The Journal is a digitalized personal journal application built with Laravel and MVC pattern that allows users to write, read, edit, and delete their journal entries.

## 🤖 AI Chabot Integration (Lab Assignment 3)
This journal application features a fully integrated AI Journal Assistant powered by Groq AI designed to help users reflect on their journal entries.

### AI Features
* **Floating Chat Widget:** A seamless, non-intrusive UI that floats on the bottom-right of the dashboard page.
* **Context-Aware Inquiries:** The AI assistant answers questions based strictly on the user's own journal entries.
* **API Isolation:** the AI never connects to the database. It triggers a secure backend Laravel API endpoint `/api/users{id}/journals` which fetches the data locally via Eloquent and returns JSON context to the AI model.
* **Multilingual Support:** The assistant can converse and understand context in English, Tagalog, Hiligaynon, and Taglish.

### AI Service Used
* **Provider:** Groq API
* **Model:** llama-3.3-70b-versatile

### Setup Instructions
To enable AI chatbot functionality, configure a Groq API Key first.
1. Visit [Google AI Studio](https://aistudio.google.com/) and sign in with your Google account.
2. Click **Get API Key** and generate a new key. Copy the API key.
3. Open your `.env` file and paste the API key on your `GROQ_API_KEY=your_generated_api_key_here`.

### Example Queries To Try
* *"What did I write about last March?"*
* *"Show me all entries where my mood was Happy."*
* *"Ilan ang mga journal entries na sinulat ko noong Abril?"*
* *"Did I mention about strategy?"*

### Screenshots
![AI Chatbot](/public/images/screenshots/chatbot-widget.png)
**Dashboard Page w/ AI Journal Chatbot**

![Expanded AI Chatbot](/public/images/screenshots/ai-chatbox.png)
**Expanded AI Journal Chatbot**

---

## ⚙️ Installation and Setup
**Step 1. Clone the repository**
* Open *Command Prompt* in Windows or *Terminal* on Mac.
* Type or copy these commands below.
```bash
git clone https://github.com/CMSC-129-Laboratory-Assignments/CMSC129-Lab2-SombitoC-DelaCruzSM
cd CMSC129-Lab2-SombitoC-DelaCruzSM
```

**Step 2. Install PHP and Node dependencies**
```
composer install      # installs the PHP packages
npm install           # This will download the node_modules folder
```

**Step 3. Setup Environment Variables**
* Duplicate the `.env.example` file from the root directory and rename it to `.env`.
* Generate the application key with this command:
```
php artisan key:generate
```
The generated key will be automatically pasted in your `.env` file.

### Run Frontend
To compile the CSS and JS files via Vite, open a new terminal and run this command:
```
npm run dev
```

---

## 🗃️ Database Setup Guide (PostgreSQL Database via Supabase)
For this application, we use PostgreSQL hosted on **Supabase**.

To successfully connect the application to the database, you must configure your environment variables.

Step 1. Open your .env file
Open the .env file you created during the installation process in your code editor.

Step 2. Update Database Configuration
Locate the block of variables starting with DB_CONNECTION. Replace the default MySQL/SQLite settings with the following Supabase PostgreSQL credentials:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=


(Note: Ensure there are no spaces around the = signs).
---

### Migration Commands
* Built the database tables using this command:
```
php artisan migrate:fresh --seed
```

### Run the Server
* Open a new terminal and run this command:
```
php artisan serve
```
* To open the application, *Ctrl + click* the link provided by the server:
    * `http://localhost:8000`
* To stop the application from running, type *Ctrl + c*.

---

## 📸 Screenshots

![Dashboard View](/public/images/screenshots/dashboard.png)
**Dashboard Page**

![Recently Deleted View](/public/images/screenshots/recently_deleted.png)
**Recently Deleted Page**

---

## 🚀 Features Implemented
* **User Authentication:** Secure signup, login, and logout functionality.
* **Journal Entry CRUD:** Create, read, update, and delete personal journal entries.
* **Dashboard Page:** Display sidebar and the journal entries written by the user.
* **Profile Management:** Users can update their username, email, and change their passwords.
* **Trash & Recovery (Soft & Hard Deletes):** Deleted entries are moved to a "Recently Deleted" page where journal entries can be restored or permanently deleted.

---

## 📂 MVC Architecture & Project Structure

This application follows Laravel's **Model-View-Controller (MVC)** architecture to separate concerns and keep the codebase clean:
* **Models (`app/Models`):** Handle data logic, database interactions, and relationships (e.g., `User` and `Journal` models).
* **Views (`resources/views`):** The frontend Blade templates that display the UI to the user.
* **Controllers (`app/Http/Controllers`):** The middleman that processes incoming HTTP requests, fetches data from the Models, and passes it to the Views.

Here is an overview of our project structure:

# Repository Structure

```
├── app
│   ├── Http
│   │   └── Controllers
│   │       ├── Controller.php
│   │       ├── JournalController.php
│   │       ├── LoginController.php
│   │       └── SignupController.php
│   │       └── SignupController.php
│   ├── Models
│   │   ├── Journal.php
│   │   └── User.php
│   └── Providers
│       └── AppServiceProvider.php
├── bootstrap
│   ├── cache
│   │   └── .gitignore
│   ├── app.php
│   └── providers.php
├── config
│   ├── app.php
│   ├── auth.php
│   ├── cache.php
│   ├── database.php
│   ├── filesystems.php
│   ├── logging.php
│   ├── mail.php
│   ├── queue.php
│   ├── services.php
│   └── session.php
├── database
│   ├── factories
│   │   └── UserFactory.php
│   ├── migrations
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   └── 2026_03_21_124204_create_journals_table.php
│   ├── seeders
│   │   └── DatabaseSeeder.php
│   └── .gitignore
├── public
│   ├── images
│   │   ├── background.jpg
│   │   ├── journal.png
│   │   └── logo.png
│   ├── .htaccess
│   ├── favicon.ico
│   ├── index.php
│   └── robots.txt
├── resources
│   ├── css
│   │   ├── app.css
│   │   ├── auth-form.css
│   │   ├── dashboard-content.css
│   │   ├── left-sidebar.css
│   │   ├── profile-page.css
│   │   ├── recently-deleted.css
│   │   └── search-bar.css
│   ├── js
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views
│       ├── components
│       │   ├── auth-form.blade.php
│       │   ├── journal-summary.blade.php
│       │   ├── journal.blade.php
│       │   ├── left-sidebar.blade.php
│       │   ├── profile-button.blade.php
│       │   └── search-bar.blade.php
│       └── layouts
│           ├── app.blade.php
│           ├── dashboard.blade.php
│           ├── login.blade.php
│           ├── profile.blade.php
│           ├── recently-deleted.blade.php
│           └── sign-up.blade.php
├── routes
│   ├── console.php
│   └── web.php
├── storage
│   ├── app
│   │   ├── private
│   │   │   └── .gitignore
│   │   ├── public
│   │   │   └── .gitignore
│   │   └── .gitignore
│   ├── framework
│   │   ├── cache
│   │   │   ├── data
│   │   │   │   └── .gitignore
│   │   │   └── .gitignore
│   │   ├── sessions
│   │   │   └── .gitignore
│   │   ├── testing
│   │   │   └── .gitignore
│   │   ├── views
│   │   │   └── .gitignore
│   │   └── .gitignore
│   └── logs
│       └── .gitignore
├── tests
│   ├── Feature
│   │   └── ExampleTest.php
│   ├── Unit
│   │   └── ExampleTest.php
│   └── TestCase.php
├── .editorconfig
├── .env.example
├── .gitattributes
├── .gitignore
├── .styleci.yml
├── artisan
├── CHANGELOG.md
├── composer.json
├── composer.lock
├── package-lock.json
├── package.json
├── phpunit.xml
├── README.md
└── vite.config.js
