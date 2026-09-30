<div class="container py-5" style="padding-bottom: 3em !important">
    <?php if (isset($_GET['success'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success">
                <?php if ($_GET['success'] === 'picture_updated'): ?> Profile picture
                updated successfully. <?php elseif ($_GET['success'] ===
                                          'picture_deleted'): ?> Profile picture deleted successfully. <?php
                                       elseif ($_GET['success'] === 'profile_updated'): ?> Profile information
                updated successfully. <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?> <?php if (isset($_GET['error'])): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger">
                <?php if ($_GET['error'] === 'invalid_url'): ?> Please provide a valid
                image URL. <?php elseif ($_GET['error'] === 'invalid_file'): ?> Invalid
                image file. Please use PNG, JPG, JPEG, WEBP, or ICO. <?php elseif
                                                                     ($_GET['error'] === 'invalid_name'): ?> Please provide a
                valid name.
                <?php elseif ($_GET['error'] === 'invalid_email'): ?> Please provide a
                valid email address. <?php elseif ($_GET['error'] === 'email_taken'): ?>
                This email address is already being used. <?php elseif ($_GET['error']
                                                              === 'invalid_profile'): ?> The profile information is invalid. <?php
                                     endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="row g-4">
        <div class="col-12 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h4 mb-4">Profile Information</h2>
                    <div id="profile-information">
                        <div class="mb-4 d-flex active justify-content-between align-items-center">
                            <div>
                                <strong>Name</strong>
                                <p class="text-muted mb-0"><?php echo escape($user->name) ?></p>
                            </div>
                            <img src="<?php echo escape($user->getProfilePicture()) ?>"
                                alt="<?php echo escape($user->name) ?>'s profile picture"
                                class="rounded-circle shadow-sm" width="80" height="80" style="object-fit: cover" />
                        </div>
                        <div class="mb-3">
                            <strong>Email</strong>
                            <p class="text-muted mb-0"><?php echo escape($user->email) ?></p>
                        </div>
                        <div class="mb-4">
                            <strong>Role</strong>
                            <p class="text-muted mb-0">
                                <?php echo $user->id_role !== null ? escape((string)
                                    $user->id_role) : 'Unknown' ?>
                            </p>
                        </div>
                        <button type="button" id="edit-profile-button" class="btn btn-outline-primary">
                            Edit Profile
                        </button>
                    </div>
                    <div id="edit-profile-form" style="display: none">
                        <form action="/profile/update" method="POST">
                            <div class="mb-3">
                                <label for="profile_name" class="form-label"> Name </label>
                                <input type="text" name="name" id="profile_name" class="form-control"
                                    value="<?php echo escape($user->name) ?>" maxlength="100" required />
                            </div>
                            <div class="mb-3">
                                <label for="profile_email" class="form-label"> Email </label>
                                <input type="email" name="email" id="profile_email" class="form-control"
                                    value="<?php echo escape($user->email) ?>" required />
                            </div>
                            <div class="mb-4">
                                <label class="form-label"> Role </label>
                                <input type="text" class="form-control"
                                    value="<?php echo $user->id_role !== null ? escape((string) $user->id_role) : 'Unknown' ?>"
                                    disabled />
                                <div class="form-text">Your role cannot be changed here.</div>
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
                    <hr class="my-4" />
                    <h2 class="h4 mb-4">Profile Picture</h2>
                    <form action="/profile/picture" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label d-block"> Picture source </label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="picture_type" id="picture_type_file"
                                    value="file" checked />
                                <label class="form-check-label" for="picture_type_file">
                                    Upload file
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="picture_type" id="picture_type_url"
                                    value="url" />
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
                                accept=".png,.jpg,.jpeg,.webp,.ico" />
                            <div class="form-text">PNG, JPG, JPEG, WEBP or ICO.</div>
                        </div>
                        <div id="url-picture-input" class="mb-3" style="display: none">
                            <label for="profile_picture_url" class="form-label">
                                Image URL
                            </label>
                            <input type="url" name="profile_picture_url" id="profile_picture_url" class="form-control"
                                placeholder="https://example.com/image.jpg" />
                            <div class="form-text">
                                The URL must point directly to an image.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Save Profile Picture
                        </button>
                    </form>
                    <?php if ($user->profile_picture !== null): ?>
                    <hr class="my-4" />
                    <form action="/profile/picture/delete" method="POST">
                        <button type="submit" class="btn btn-outline-danger" onclick="
                return confirm(
                  'Are you sure you want to delete your profile picture?',
                );
              ">
                            Delete Profile Picture
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 mb-0">My Recipes</h2>
                        <a href="/recipe/create" class="btn btn-primary btn-sm">
                            + Add Recipe
                        </a>
                    </div>
                    <div style="max-height: 650px; overflow-y: auto; padding-right: 8px">
                        <?php if (empty($recipes)): ?>
                        <div class="text-center py-5">
                            <div class="text-muted mb-3">
                                You haven't created any recipes yet.
                            </div>
                            <a href="/recipe/create" class="btn btn-outline-primary">
                                Create your first recipe
                            </a>
                        </div>
                        <?php else: ?> <?php foreach ($recipes as $recipe): ?>
                        <div class="card mb-3 border">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <h3 class="h5 mb-2">
                                            <a href="/recipe/<?php echo escape($recipe->id) ?>"
                                                class="text-decoration-none">
                                                <?php echo escape($recipe->name) ?>
                                            </a>
                                        </h3>
                                        <?php if ($recipe->category !== null): ?>
                                        <span class="badge text-bg-secondary mb-2">
                                            <?php echo escape($recipe->category) ?>
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($recipe->averageRating !== null): ?>
                                    <span class="badge text-bg-warning">
                                        ★ <?php echo number_format($recipe->averageRating, 1) ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted mb-3">
                                    <?php echo escape(strlen($recipe->description) > 180 ?
                                            substr($recipe->description, 0, 180) . '...' :
                                        $recipe->description) ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <?php echo count($recipe->ingredients) ?> ingredient<?php
           echo count($recipe->ingredients) !== 1 ? 's' : '' ?>
                                    </small>
                                    <a href="/recipe/<?php echo escape($recipe->id) ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        View recipe
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?> <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>