[supervisord]
nodaemon=true
user=root
logfile=/dev/stdout
logfile_maxbytes=0

[program:laravel-horizon]
process_name=%(program_name)s
command=php /var/www/html/backend/artisan horizon
autostart=true
autorestart=true
user=root
redirect_stderr=true
stdout_logfile=/horizon.log
stopwaitsecs=3600

[eventlistener:supervisord-watchdog]
command=/usr/bin/python3 /opt/supervisord-watchdog.py
events=PROCESS_STATE_FATAL
