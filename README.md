# 🌍 ÉVASION – AI Travel Planner

A dynamic travel agency web application built with **PHP**, **MySQL**, **HTML/CSS/JS**, and the **Mistral AI** and **Pixabay** APIs.  
Users can describe their dream trip in natural language and receive a completely AI-generated, personalized travel suggestion.

![GitHub repo size](https://img.shields.io/github/repo-size/ton-utilisateur/evasion-ai-travel-planner)
![GitHub last commit](https://img.shields.io/github/last-commit/ton-utilisateur/evasion-ai-travel-planner)
![License](https://img.shields.io/badge/license-MIT-blue)

## ✨ Features

- **AI-Powered Trip Generation** – Describe your ideal vacation (e.g., “a romantic weekend in Europe, budget-friendly, with beaches”) and Mistral AI creates a unique trip including destination, dates, price, and description.
- **User Authentication** – Secure registration and login with password hashing (bcrypt).
- **Profile Management** – Update name, email, password, and upload a profile picture.
- **Travel Cart** – Add AI-generated or featured trips to your cart, view total price, and remove items.
- **Responsive Design** – Mobile-friendly interface with hamburger menu and glassmorphism styling.
- **Interactive Search Tabs** – Tabs for flights, hotels, and AI discovery (front-end only, hotel and flight are demo).
- **Autocomplete** – City suggestions powered by Open-Meteo Geocoding API.
- **Testimonials Carousel** – Animated customer reviews.

## 🛠️ Tech Stack

| Frontend | Backend | Database | APIs & Tools |
|----------|---------|----------|--------------|
| HTML5, CSS3, JavaScript | PHP (vanilla) | MySQL / MariaDB | Mistral AI (mistral-large-latest) |
| Font Awesome, Google Fonts | Sessions, PDO | phpMyAdmin | Pixabay API (images) |
| Responsive (Flexbox/Grid) | cURL |  | Open-Meteo (city autocomplete) |

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) (or any PHP + MySQL environment)
- A Mistral AI API key ([get one here](https://console.mistral.ai/))
- A Pixabay API key ([get one here](https://pixabay.com/api/docs/))

### Installation

1. **Clone the repository** into your `htdocs` folder (or equivalent):
   ```bash
   git clone https://github.com/ton-utilisateur/evasion-ai-travel-planner.git evasion
