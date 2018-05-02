<?php

declare(strict_types=1);

return [
    'siteName' => 'Yii2 Gallery',
    'adminEmail' => 'admin@yii2gallery.com',
    'supportEmail' => 'support@yii2gallery.com',
    'passwordResetTokenExpire' => 3600, // 1 hour
    'secondsToRemember' => 2592000, // 30 days
    'maxFileSize' => 2097152, // 2 MiB
    'storagePath' => '@app/web/uploads/',
    'storageUri' => '/uploads/',
    'defaultPicture' => '/img/profile_default_image.png',
];
