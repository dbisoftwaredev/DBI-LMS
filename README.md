# Noodle Project (Moodle)

This is a containerized Moodle environment.

## Prerequisites

- [Docker](https://www.docker.com/products/docker-desktop)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Getting Started

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd noodle_project
    ```

2.  **Start the environment:**
    ```bash
    docker-compose up -d
    ```

3.  **Access Moodle:**
    Open your browser and navigate to: [http://localhost:80](http://localhost:80)

## Configuration

- **Database**: MariaDB (Service: `mariadb`)
- **Moodle Source**: Mapped to local `./moodle` directory. Changes to files in this directory will be reflected in the container.
- **Moodle Data**: Persisted in Docker volume `moodledata_data`.
- **Database Data**: Persisted in Docker volume `mariadb_data`.

## Credentials

(See `docker-compose.yml` for default environment variables)

- **Admin User**: `admin`
- **Admin Password**: `bitnami` (Default, change on first login if prompted)
- **Database User**: `bn_moodle`
- **Database Password**: (Empty allowed in dev)

## Troubleshooting

- **Check logs:**
    ```bash
    docker-compose logs -f moodle
    ```
- **Restart containers:**
    ```bash
    docker-compose restart
    ```
