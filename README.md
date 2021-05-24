# Fregata demo project

Simple [Fregata](https://github.com/AymDev/Fregata) v1 project setup using **Docker Compose** with a migration from a
**MySQL** to a **PostgreSQL** database.

# Installation

Clone this repository:
```shell
git clone https://github.com/AymDev/Fregata-demo.git
```

Or install it from **Composer**:
```shell
composer create-project aymdev/fregata-demo
```

Use the **Makefile** commands to build, start and open a Bash session into the app:
```shell
make start && make shell
```

# Usage
A **demo_migration** is provided with this project. It will create (or re-create) the *source* (MySQL) and *target*
(PostgreSQL) databases, and generate fake data in the *source*.

Run the migration:
```shell
php vendor/bin/fregata migration:execute demo_migration
```
