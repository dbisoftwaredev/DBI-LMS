@echo off
echo Starting Moodle on http://localhost:8000
echo Configuration:
echo - max_execution_time: 300
echo - memory_limit: 512M
echo - upload_max_filesize: 100M
echo - post_max_size: 100M
echo - max_input_vars: 5000

php -d max_execution_time=300 -d memory_limit=512M -d upload_max_filesize=100M -d post_max_size=100M -d max_input_vars=5000 -S localhost:8000 -t moodle
