<div class="container py-5" style="padding-bottom: 3em!important;">

    <?php if (isset($_GET['success'])): ?>
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="alert alert-success">
                    <?php if ($_GET['success'] === 'picture_updated'): ?>
                        Profile picture updated successfully.
                    <?php elseif ($_GET['success'] === 'picture_deleted'): ?>
                        Profile picture deleted successfully.
                    <?php elseif ($_GET['success'] === 'profile_updated'): ?>
                        Profile information updated successfully.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="alert alert-danger">
                    <?php if ($_GET['error'] === 'invalid_url'): ?>
                        Please provide a valid image URL.
                    <?php elseif ($_GET['error'] === 'invalid_file'): ?>
                        Invalid image file. Please use PNG, JPG, JPEG,
                        WEBP, or ICO.
                    <?php elseif ($_GET['error'] === 'invalid_name'): ?>
                        Please provide a valid name.
                    <?php elseif ($_GET['error'] === 'invalid_email'): ?>
                        Please provide a valid email address.
                    <?php elseif ($_GET['error'] === 'email_taken'): ?>
                        This email address is already being used.
                    <?php elseif ($_GET['error'] === 'invalid_profile'): ?>
                        The profile information is invalid.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-4">

                    <h2 class="h4 mb-4">
                        Profile Information
                    </h2>

                    <div id="profile-information">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Name</strong>
                                <p class="text-muted mb-0">
                                    <?= escape($user->name) ?>
                                </p>
                            </div>

                            <img src="<?= escape($user->getProfilePicture()) ?>"
                                alt="<?= escape($user->name) ?>'s profile picture" class="rounded-circle shadow-sm"
                                width="80" height="80" style="object-fit: cover;">
                        </div>

                        <div class="mb-3">
                            <strong>Email</strong>
                            <p class="text-muted mb-0">
                                <?= escape($user->email) ?>
                            </p>
                        </div>

                        <div class="mb-4">
                            <strong>Role</strong>
                            <p class="text-muted mb-0">
                                <?= $user->id_role !== null
                                    ? escape((string) $user->id_role)
                                    : 'Unknown' ?>
                            </p>
                        </div>

                        <button type="button" id="edit-profile-button" class="btn btn-outline-primary">
                            Edit Profile
                        </button>
                    </div>

                    <div id="edit-profile-form" style="display: none;">
                        <form action="/profile/update" method="POST">

                            <div class="mb-3">
                                <label for="profile_name" class="form-label">
                                    Name
                                </label>

                                <input type="text" name="name" id="profile_name" class="form-control"
                                    value="<?= escape($user->name) ?>" maxlength="100" required>
                            </div>

                            <div class="mb-3">
                                <label for="profile_email" class="form-label">
                                    Email
                                </label>

                                <input type="email" name="email" id="profile_email" class="form-control"
                                    value="<?= escape($user->email) ?>" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">
                                    Role
                                </label>

                                <input type="text" class="form-control" value="<?= $user->id_role !== null
                                    ? escape((string) $user->id_role)
                                    : 'Unknown' ?>" disabled>

                                <div class="form-text">
                                    Your role cannot be changed here.
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Save Changes
                                </button>

                                <button type="button" id="cancel-edit-profile" class="btn btn-outline-secondary">
                                    Cancel
                                </button>
                            </div>

                        </form>
                    </div>

                    <hr class="my-4">

                    <h2 class="h4 mb-4">
                        Profile Picture
                    </h2>

                    <form action="/profile/picture" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label d-block">
                                Picture source
                            </label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="picture_type" id="picture_type_file"
                                    value="file" checked>

                                <label class="form-check-label" for="picture_type_file">
                                    Upload file
                                </label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="picture_type" id="picture_type_url"
                                    value="url">

                                <label class="form-check-label" for="picture_type_url">
                                    Image URL
                                </label>
                            </div>
                        </div>

                        <div id="file-picture-input" class="mb-3">
                            <label for="profile_picture" class="form-label">
                                Choose an image
                            </label>

                            <input type="file" name="profile_picture" id="profile_picture" class="form-control"
                                accept=".png,.jpg,.jpeg,.webp,.ico">

                            <div class="form-text">
                                PNG, JPG, JPEG, WEBP or ICO.
                            </div>
                        </div>

                        <div id="url-picture-input" class="mb-3" style="display: none;">
                            <label for="profile_picture_url" class="form-label">
                                Image URL
                            </label>

                            <input type="url" name="profile_picture_url" id="profile_picture_url" class="form-control"
                                placeholder="https://example.com/image.jpg">

                            <div class="form-text">
                                The URL must point directly to an image.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Save Profile Picture
                        </button>
                    </form>

                    <?php if ($user->profile_picture !== null): ?>
                        <hr class="my-4">

                        <form action="/profile/picture/delete" method="POST">
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm(
                                    'Are you sure you want to delete your profile picture?'
                                );">
                                Delete Profile Picture
                            </button>
                        </form>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileRadio = document.getElementById('picture_type_file');
        const urlRadio = document.getElementById('picture_type_url');
        const fileContainer = document.getElementById('file-picture-input');
        const urlContainer = document.getElementById('url-picture-input');
        const fileInput = document.getElementById('profile_picture');
        const urlInput = document.getElementById('profile_picture_url');

        function updatePictureInput() {
            if (fileRadio.checked) {
                fileContainer.style.display = 'block';
                urlContainer.style.display = 'none';
                fileInput.required = true;
                urlInput.required = false;
                urlInput.value = '';
            } else {
                fileContainer.style.display = 'none';
                urlContainer.style.display = 'block';
                fileInput.required = false;
                urlInput.required = true;
                fileInput.value = '';
            }
        }

        fileRadio.addEventListener('change', updatePictureInput);
        urlRadio.addEventListener('change', updatePictureInput);
        updatePictureInput();

        const profileInformation =
            document.getElementById('profile-information');

        const editProfileForm =
            document.getElementById('edit-profile-form');

        const editProfileButton =
            document.getElementById('edit-profile-button');

        const cancelEditProfile =
            document.getElementById('cancel-edit-profile');

        editProfileButton.addEventListener('click', function () {
            profileInformation.style.display = 'none';
            editProfileForm.style.display = 'block';
        });

        cancelEditProfile.addEventListener('click', function () {
            editProfileForm.style.display = 'none';
            profileInformation.style.display = 'block';
        });

        const successAlert =
            document.querySelector('.alert-success');

        if (successAlert) {
            setTimeout(function () {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';

                setTimeout(function () {
                    successAlert.remove();
                }, 500);

                window.location.href = '/profile';
            }, 3000);
        }
    });
</script>