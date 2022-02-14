<?php
namespace Deployer;

require 'recipe/symfony4.php';

// Project name
set('application', 'etreenphase');

// Project repository
set('repository', 'git@github.com:naoydu54/etreenphase.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', false);
set('writable_mode', 'chmod');
set('symfony_version', function () {
    $result = run('{{bin/console}} --version');
    preg_match_all('/(\d+\.?)+/', $result, $matches);
    return $matches[0][0] ?? 5.0;
});

// Shared files/dirs between deploys
add('shared_files', [ '.env.local']);
add('shared_dirs', ['public/pdf', 'public/uploads', 'public/uploadElfinder', 'var/log', 'var/sessions']);

// Writable dirs by web server
add('writable_dirs', ['public/pdf', 'public/uploads', 'public/uploadElfinder', 'var/log', 'var/sessions', 'var']);


//host('production')
//    ->hostname('198.100.146.180')
//    ->user('root')
//    ->stage('production')
//    ->set('branch', 'master')
//    ->forwardAgent(true)
//    ->set('deploy_path', '/var/www/boutique-cse-amf.fr/web');

host('production')
    ->hostname('207.180.195.89')
    ->user('root')
    ->stage('production')
    ->set('branch', 'master')
    ->forwardAgent(true)
    ->set('deploy_path', '/var/www/vhosts/etreenphase.conceptsiteweb.com/httpdocs');


//task('app:chown', function () {
//    run('cd {{deploy_path}} && chown -R web6:client0 .');
//});
task('app:chown', function () {
    run('cd {{deploy_path}} && chown -R sysuser_5:psacln .');
});

//task('app:backup', function (){
//    run('cd /home/backup/amf/sql && mysqldump -uamf -pU_le30e0 amf >  amf_$(date "+%b_%d_%Y_%H_%M_%S").sql ');
//
//});
//task('app:backupFile', function (){
//    run('cp -R {{deploy_path}}/shared/ /home/backup/amf/file/$(date "+%b_%d_%Y_%H_%M_%S")');
//
//});

task('app:chown', function () {
    run('cd {{deploy_path}} && chown -R sysuser_5:psacln .');
});

task('app:ckeditorInstall', function () {
    run('cd {{deploy_path}}/current && php bin/console ckeditor:install ');
});

task('app:elfinder', function () {
    run('cd {{deploy_path}}/current && /usr/local/bin/symfony console elfinder:install');
});

task('app:ckeditorLink', function () {
    run('cd {{deploy_path}}/current && php bin/console assets:install public ');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
//before('database:migrate', 'app:backup');
//after('app:backup', 'app:backupFile');

before('deploy:symlink', 'database:migrate');
before('deploy:symlink', 'app:chown');
before('cleanup', 'app:ckeditorInstall');
after('app:ckeditorInstall', 'app:ckeditorLink');
after('app:ckeditorLink', 'app:elfinder');
