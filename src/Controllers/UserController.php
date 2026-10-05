<?php

declare (strict_types = 1);

namespace BastienJcln\SwissCooking\Controllers;

use BastienJcln\SwissCooking\Models\Recipe;
use BastienJcln\SwissCooking\Models\User;
use BastienJcln\SwissCooking\Models\Role;
use BastienJcln\SwissCooking\Services\ConnexionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController
{
    public function profile(
        Request $request,
        Response $response
    ): Response {
        $user = ConnexionService::connectedUser();

        if ($user === null) {
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        if ($user->id !== (int) ($_SESSION['user_id'] ?? 0)) {
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        $recipes = [];

        if ($user->id !== null) {
            $recipes = Recipe::getRecipesByUserId($user->id);
        }

        $roles = Role::getAllRoles();

        return $this->view->render($response, 'user/profile.php', [
            'user'    => $user,
            'roles'   => $roles,
            'recipes' => $recipes,
        ]);
    }

    public function publicProfile(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $userId = (int) ($args['id'] ?? 0);

        if ($userId <= 0) {
            return $response
                ->withHeader('Location', '/404')
                ->withStatus(302);
        }

        $user = User::findById($userId);

        if ($user === null) {
            return $response
                ->withHeader('Location', '/404')
                ->withStatus(302);
        }

        $recipes = Recipe::getRecipesByUserId($user->id);

        $roles = Role::getAllRoles();

        return $this->view->render($response, 'user/profile.php', [
            'user'    => $user,
            'roles'   => $roles,
            'recipes' => $recipes,
        ]);
    }

    public function updateProfile(
        Request $request,
        Response $response
    ): Response {
        $user = ConnexionService::connectedUser();

        if ($user === null) {
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        $data = $request->getParsedBody() ?? [];

        $name  = trim((string) ($data['name'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));

        if ($name === '' || strlen($name) > 100) {
            return $response
                ->withHeader('Location', '/profile?error=invalid_name')
                ->withStatus(302);
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $response
                ->withHeader('Location', '/profile?error=invalid_email')
                ->withStatus(302);
        }

        $existingUser = User::findByEmail($email);

        if (
            $existingUser !== null &&
            $existingUser->id !== $user->id
        ) {
            return $response
                ->withHeader('Location', '/profile?error=email_taken')
                ->withStatus(302);
        }

        try {
            $user->name  = $name;
            $user->email = $email;
            $user->update();
        } catch (\InvalidArgumentException $e) {
            return $response
                ->withHeader('Location', '/profile?error=invalid_profile')
                ->withStatus(302);
        }

        return $response
            ->withHeader('Location', '/profile?success=profile_updated')
            ->withStatus(302);
    }

    public function updateProfilePicture(
        Request $request,
        Response $response
    ): Response {
        $user = ConnexionService::connectedUser();

        if ($user === null) {
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        $data        = $request->getParsedBody() ?? [];
        $pictureType = $data['picture_type'] ?? null;

        if ($pictureType === 'url') {
            $url = trim(
                (string) ($data['profile_picture_url'] ?? '')
            );

            $result = $this->downloadProfilePictureFromUrl(
                $url,
                $user->name
            );

            if ($result === null) {
                return $response
                    ->withHeader(
                        'Location',
                        '/profile?error=invalid_url'
                    )
                    ->withStatus(302);
            }

            $this->deleteLocalProfilePicture($user);

            $user->profile_picture = $result;
            $user->update();

            return $response
                ->withHeader(
                    'Location',
                    '/profile?success=picture_updated'
                )
                ->withStatus(302);
        }

        if ($pictureType === 'file') {
            $uploadedFiles = $request->getUploadedFiles();
            $file          = $uploadedFiles['profile_picture'] ?? null;

            if (
                $file === null ||
                $file->getError() !== UPLOAD_ERR_OK
            ) {
                return $response
                    ->withHeader(
                        'Location',
                        '/profile?error=invalid_file'
                    )
                    ->withStatus(302);
            }

            $allowedExtensions = [
                'png',
                'jpg',
                'jpeg',
                'webp',
                'ico',
            ];

            $extension = strtolower(
                pathinfo(
                    $file->getClientFilename(),
                    PATHINFO_EXTENSION
                )
            );

            if (! in_array($extension, $allowedExtensions, true)) {
                return $response
                    ->withHeader(
                        'Location',
                        '/profile?error=invalid_file'
                    )
                    ->withStatus(302);
            }

            $tmpPath  = $file->getStream()->getMetadata('uri');
            $mimeType = mime_content_type($tmpPath);

            $allowedMimeTypes = [
                'image/png',
                'image/jpeg',
                'image/webp',
                'image/x-icon',
                'image/vnd.microsoft.icon',
            ];

            if (! in_array($mimeType, $allowedMimeTypes, true)) {
                return $response
                    ->withHeader(
                        'Location',
                        '/profile?error=invalid_file'
                    )
                    ->withStatus(302);
            }

            $uploadDirectory = dirname(__DIR__, 2)
                . '/public/upload/profile_pic/';

            if (! is_dir($uploadDirectory)) {
                if (
                    ! mkdir($uploadDirectory, 0775, true) &&
                    ! is_dir($uploadDirectory)
                ) {
                    throw new \RuntimeException(
                        'Could not create profile picture directory'
                    );
                }
            }

            if (! is_writable($uploadDirectory)) {
                throw new \RuntimeException(
                    'Upload directory is not writable: '
                    . $uploadDirectory
                );
            }

            $safeUsername = preg_replace(
                '/[^a-zA-Z0-9_-]/',
                '_',
                $user->name
            );

            $filename = $safeUsername . '_pfp.' . $extension;

            $this->deleteLocalProfilePicture($user);

            foreach ($allowedExtensions as $oldExtension) {
                $oldFile = $uploadDirectory
                    . $safeUsername
                    . '_pfp.'
                    . $oldExtension;

                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }

            $targetPath = $uploadDirectory . $filename;

            $file->moveTo($targetPath);

            $user->profile_picture =
                'upload/profile_pic/' . $filename;

            $user->update();

            return $response
                ->withHeader(
                    'Location',
                    '/profile?success=picture_updated'
                )
                ->withStatus(302);
        }

        return $response
            ->withHeader(
                'Location',
                '/profile?error=invalid_type'
            )
            ->withStatus(302);
    }

    private function downloadProfilePictureFromUrl(
        string $url,
        string $username
    ): ?string {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);

        if (
            ! isset($parts['scheme']) ||
            ! in_array(
                strtolower($parts['scheme']),
                ['http', 'https'],
                true
            )
        ) {
            return null;
        }

        if (! function_exists('curl_init')) {
            return null;
        }

        $temporaryFile = tempnam(
            sys_get_temp_dir(),
            'swisscooking_pfp_'
        );

        if ($temporaryFile === false) {
            return null;
        }

        $handle = fopen($temporaryFile, 'wb');

        if ($handle === false) {
            unlink($temporaryFile);
            return null;
        }

        $curl = curl_init($url);

        if ($curl === false) {
            fclose($handle);
            unlink($temporaryFile);
            return null;
        }

        curl_setopt_array($curl, [
            CURLOPT_FILE           => $handle,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_USERAGENT      =>
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 Chrome/140 Safari/537.36',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_FAILONERROR    => false,
            CURLOPT_HEADER         => false,
        ]);

        $success  = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        fclose($handle);

        if ($success === false) {
            unlink($temporaryFile);
            return null;
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            unlink($temporaryFile);
            return null;
        }

        if (! is_file($temporaryFile)) {
            return null;
        }

        $fileSize = filesize($temporaryFile);

        if ($fileSize === false || $fileSize === 0) {
            unlink($temporaryFile);
            return null;
        }

        $finfo    = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($temporaryFile);

        $mimeToExtension = [
            'image/png'                => 'png',
            'image/jpeg'               => 'jpg',
            'image/webp'               => 'webp',
            'image/gif'                => 'gif',
            'image/x-icon'             => 'ico',
            'image/vnd.microsoft.icon' => 'ico',
        ];

        if (! isset($mimeToExtension[$mimeType])) {
            unlink($temporaryFile);
            return null;
        }

        $extension = $mimeToExtension[$mimeType];

        $uploadDirectory = dirname(__DIR__, 2)
            . '/public/upload/profile_pic/';

        if (! is_dir($uploadDirectory)) {
            if (
                ! mkdir($uploadDirectory, 0775, true) &&
                ! is_dir($uploadDirectory)
            ) {
                unlink($temporaryFile);
                return null;
            }
        }

        if (! is_writable($uploadDirectory)) {
            unlink($temporaryFile);
            return null;
        }

        $safeUsername = preg_replace(
            '/[^a-zA-Z0-9_-]/',
            '_',
            $username
        );

        $filename    = $safeUsername . '_pfp.' . $extension;
        $destination = $uploadDirectory . $filename;

        foreach ([
            'png',
            'jpg',
            'jpeg',
            'webp',
            'gif',
            'ico',
        ] as $oldExtension) {
            $oldFile = $uploadDirectory
                . $safeUsername
                . '_pfp.'
                . $oldExtension;

            if (is_file($oldFile)) {
                unlink($oldFile);
            }
        }

        if (! rename($temporaryFile, $destination)) {
            unlink($temporaryFile);
            return null;
        }

        return 'upload/profile_pic/' . $filename;
    }

    private function deleteLocalProfilePicture($user): void
    {
        if ($user->profile_picture === null) {
            return;
        }

        if (
            str_starts_with($user->profile_picture, 'http://') ||
            str_starts_with($user->profile_picture, 'https://')
        ) {
            return;
        }

        $profileDirectory = realpath(
            dirname(__DIR__, 2)
            . '/public/upload/profile_pic/'
        );

        if ($profileDirectory === false) {
            return;
        }

        $path = dirname(__DIR__, 2)
        . '/public/'
        . ltrim($user->profile_picture, '/');

        $realPath = realpath($path);

        if (
            $realPath !== false &&
            str_starts_with(
                $realPath,
                $profileDirectory . DIRECTORY_SEPARATOR
            ) &&
            is_file($realPath)
        ) {
            unlink($realPath);
        }
    }

    public function deleteProfilePicture(
        Request $request,
        Response $response
    ): Response {
        $user = ConnexionService::connectedUser();

        if ($user === null) {
            return $response
                ->withHeader('Location', '/login')
                ->withStatus(302);
        }

        if ($user->profile_picture !== null) {
            $this->deleteLocalProfilePicture($user);

            $user->profile_picture = null;
            $user->update();
        }

        return $response
            ->withHeader(
                'Location',
                '/profile?success=picture_deleted'
            )
            ->withStatus(302);
    }

    public function logout(
        Request $request,
        Response $response
    ): Response {
        return ConnexionService::logout($request, $response);
    }
}
