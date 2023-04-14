[supervisord]
nodaemon=true
user=root
logfile=/dev/stdout
logfile_maxbytes=0

[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/backend/artisan queue:work --once %%QUEUE_CONNECTION%% --timeout=16000 --queue=%%QUEUE_NAME%% --memory=%%MEMORY_LIMIT%%
autostart=true
autorestart=true
numprocs=1
startretries=10
stdout_events_enabled=1
redirect_stderr=true
stdout_logfile=/laravel-worker.log


[eventlistener:supervisord-watchdog]
command=/usr/bin/python3 /opt/supervisord-watchdog.py
events=PROCESS_STATE_FATAL
