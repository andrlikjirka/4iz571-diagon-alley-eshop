# Diagon Alley E-shop

> E-shop from Harry's Potter World

## Project description

- The e-shop meant for students and teachers at Hogwarts School of Witchcraft and Wizardry,
- It allows them to buy all the necessary stuff they need for studying at the school (like cloaks, magic wands, broomsticks, books, ...). In the old days, all of this where sold at stores in Diagon Alley.

### Tech-stack

- PHP, Nette framwework
- Latte, Bootstrap 5, Naja AJAX
- Nextras ORM
- MariaDB
- Docker

### Funcionality

#### Public module
- registration process
- tree-like categories
- products list and product detail
- reviews with average review per product calculation
- prices in galleon, sickles and knuts
- favorite products
- cart
- order process
- product stock control
- invoice pdf generatation 
- email notifications

#### Administration module
- basic dashboard with statistics
- categories management
- products management
- stock (inventory) management
- orders management
- users management

## How to run the project

1. `git clone ...`
2. `cd .docker`
3. run: `docker compose up`
4. go to: `http://localhost:8095/`

## Screenshots from the app
<img src="https://github.com/andrlikjirka/diagon-alley-eshop/assets/18737702/e34fe99a-22d8-4030-933c-6bf3e735836d" width="85%">
