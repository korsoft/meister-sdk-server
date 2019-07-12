<?php
//To generate documentation just run php sami.phar configFile
use Sami\Sami;
use Sami\RemoteRepository\GitHubRemoteRepository;
use Sami\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->notName('Kernel.php')
    ->notName('EncryptCookies.php')
    ->notName('RedirectIfAuthenticated.php')
    ->notName('TrimStrings.php')
    ->notName('TrustProxies.php')
    ->notName('VerifyCsrfToken.php')
    ->notName('Controller.php')
    ->notName('ForgotPasswordController')
    ->notName('RegisterController.php')
    ->notName('ResetPasswordController.php')
    ->notName('VerifyCsrfToken.php')
    ->exclude('Resources')
    ->exclude('Console')
    ->exclude('Exceptions')
    ->exclude('Providers')
    ->in($dir = 'app/')
;


return new Sami($iterator, array(
    'title'                => 'Meister SDK',
    'build_dir'            => __DIR__.'/build',
    'cache_dir'            => __DIR__.'/cache/',
    'default_opened_level' => 2,
));