# 🌍 ÉVASION – AI Travel Planner

A dynamic travel agency web application built with **PHP**, **MySQL**, **HTML/CSS/JS**, and the **Mistral AI** and **Pixabay** APIs.  
Users can describe their dream trip in natural language and receive a completely AI-generated, personalized travel suggestion.

![License](https://img.shields.io/badge/license-MIT-blue)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?logo=mysql&logoColor=white)
![Mistral AI](https://img.shields.io/badge/Mistral_AI-mistral--large--latest-FF7000)

---

## ✨ Features

- **AI-Powered Trip Generation** – Describe your ideal vacation (e.g., *"a romantic weekend in Europe, budget-friendly, with beaches"*) and Mistral AI creates a unique trip including destination, dates, price, and description.
- **User Authentication** – Secure registration and login with password hashing (bcrypt).
- **Profile Management** – Update name, email, password, and upload a profile picture.
- **Travel Cart** – Add AI-generated or featured trips to your cart, view total price, and remove items.
- **Responsive Design** – Mobile-friendly interface with hamburger menu and glassmorphism styling.
- **Interactive Search Tabs** – Tabs for flights, hotels, and AI discovery (front-end only; hotel and flight tabs are demo).
- **Autocomplete** – City suggestions powered by Open-Meteo Geocoding API.
- **Testimonials Carousel** – Animated customer reviews.

---

## 🛠️ Tech Stack

| Frontend | Backend | Database | APIs & Tools |
|----------|---------|----------|--------------|
| HTML5, CSS3, JavaScript | PHP (vanilla) | MySQL / MariaDB | Mistral AI (`mistral-large-latest`) |
| Font Awesome, Google Fonts | Sessions, PDO | phpMyAdmin | Pixabay API (images) |
| Responsive (Flexbox/Grid) | cURL | | Open-Meteo (city autocomplete) |

---

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any PHP + MySQL environment)
- A Mistral AI API key → [console.mistral.ai](https://console.mistral.ai/)
- A Pixabay API key → [pixabay.com/api/docs](https://pixabay.com/api/docs/)

### Installation

**1. Clone the repository** into your `htdocs` folder (or equivalent):

```bash
git clone https://github.com/ton-utilisateur/evasion-ai-travel-planner.git evasion
```

**2. Import the database**

Create a database named `evasion` in MySQL, then import the provided SQL dump:

```bash
# Command line (replace 'root' with your username if different)
mysql -u root -p evasion < evasion.sql
# If your password is empty, just press Enter when prompted.
```

> **Using phpMyAdmin?** Open phpMyAdmin → create a new database named `evasion` → go to the **Import** tab → select `evasion.sql` → click **Go**.

**3. Configure your API keys**

Open `recherche_ia.php` and replace the placeholder values with your own keys:

```php
$api_key     = "YOUR_MISTRAL_API_KEY";   // ← get from https://console.mistral.ai/
$pixabay_key = "YOUR_PIXABAY_API_KEY";   // ← get from https://pixabay.com/api/docs/
```

**4. Configure your database credentials** *(if different from defaults)*

The project connects with `root` and no password by default. If your setup differs, update this line in `login.php`, `register.php`, `profil.php`, and `recherche_ia.php`:

```php
$pdo = new PDO("mysql:host=localhost;dbname=evasion;charset=utf8", "root", "");
//                                                                   ^^^^  ^^
//                                                        your username & password
```

**5. Launch the servers**

Start **Apache** and **MySQL** from the XAMPP control panel, then open your browser and navigate to:

```
http://localhost/evasion/
```

---

## 🔑 Test Account

A ready-to-use account is included in the SQL dump. Use it to explore all features immediately:

| Email | Password | Role |
|-------|----------|------|
| chat@chat.com | chat | User |

> You can also register a brand-new account from the login page.

---

## 📁 Project Structure

```
evasion/
├── index.php           # Home page with featured offers and search tabs
├── login.php           # User login
├── register.php        # User registration
├── profil.php          # Profile management (name, email, password, avatar)
├── voyages.php         # Travel cart & reservations
├── recherche_ia.php    # AI-powered trip generation (Mistral + Pixabay)
├── logout.php          # Session destruction
├── main.js             # Frontend interactivity (tabs, carousel, autocomplete)
├── style.css           # Main stylesheet (glassmorphism, responsive)
├── evasion.sql         # Full database dump (structure + seed data)
└── uploads/            # User profile pictures (auto-created on first upload)
```

---

## 🧠 How the AI Feature Works

1. The user types a free-text description in the **"Découverte"** tab (e.g., *"adventure trip in South America for two weeks"*).
2. The form submits the prompt to `recherche_ia.php` via POST.
3. The script builds a **system prompt** instructing Mistral to respond with a strict JSON object.
4. The **Mistral API** (`mistral-large-latest`) returns a structured JSON containing:
   ```json
   {
     "titre": "Amazonie Sauvage",
     "pays": "Brésil",
     "prix": 1890,
     "date_debut": "2025-07-10",
     "date_fin": "2025-07-24",
     "description": "..."
   }
   ```
5. The app queries the **Pixabay API** with the destination name to fetch a matching photo, then renders a beautiful trip card.

> **No predefined destination catalog is used** — every suggestion is generated entirely on-the-fly by the AI.

---

## 🔒 Security Measures

- **Password hashing** with `password_hash()` using the bcrypt algorithm
- **Prepared statements** via PDO to prevent SQL injection on all queries
- **Input sanitization** with `htmlspecialchars()` and `trim()` on all user inputs
- **File upload validation** — only allowed extensions are accepted; uploaded files are uniquely renamed to prevent path traversal attacks

---

## 📝 Future Improvements

- [ ] Persist cart in a `reservations` database table (currently session-based only)
- [ ] Implement a real admin panel for destination and user management
- [ ] Add Stripe payment integration (currently simulated)
- [ ] Allow multi-turn conversation history with the AI
- [ ] Deploy online (e.g., with [Infomaniak](https://www.infomaniak.com/) or [alwaysdata](https://www.alwaysdata.com/))

---

## 🙏 Acknowledgements

- [Mistral AI](https://mistral.ai/) — for the powerful language model powering trip generation
- [Pixabay](https://pixabay.com/) — for the free, high-quality stock photos
- [Open-Meteo](https://open-meteo.com/) — for the geocoding / city autocomplete API
- [Unsplash](https://unsplash.com/) — for placeholder images used during development

---

## 📄 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

<p align="center">
  Made with ❤️ by <strong>Sarah Yahou</strong> &amp; <strong>Walid Kebbache</strong><br>
  L2 MIASHS – Université Paris Nanterre
</p>
