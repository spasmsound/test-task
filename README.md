How to run:

1. Copy "docker/.env.dist" > "docker/.env"
2. Go to "docker" directory
3. Run "bin/build.sh"
4. Run "bin/up.sh"
5. Run "bin/composer.sh install"
6. Run "bin/console.sh doctrine:migrations:migrate"
7. Run "bin/console.sh doctrine:fixtures:load"
8. Run "bin/console.sh app:crawl-jobs"
9. Done. Open your browser and go to "http://localhost"