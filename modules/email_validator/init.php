<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Registra a tarefa cron para execução diária
register_cron_task('daily_email_validation', 'email_validator/EmailValidator/cron_task', 'daily');