<?= $this->layout('layout', ['myTitle' => 'profil']); ?>

<section class="profile">
    <h2 class="profile__title">Mon profil</h2>
    <form class="profile__form" method="post" action="<?= $router->generate('user_update') ?>">
        <div class="form-group">
            <span><?= $connectedUser->getEmail(); ?></span>
        </div>
        <div class="form-group">
            <label for="firstname">Prénom</label>
            <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $connectedUser->getFirstname(); ?>" placeholder="Prénom">
        </div>
        <div class="form-group">
            <label for="lastname">Nom</label>
            <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $connectedUser->getLastname(); ?>" placeholder="Nom">
        </div>
        <div class="form-group">
            <label for="phoneNumber">Numéro de téléphone</label>
            <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?= $connectedUser->getPhoneNumber(); ?>" placeholder="Numéro de téléphone">
        </div>
        <div class="form-group">
            <label for="adress">Adresse</label>
            <input type="text" class="form-control" id="adress" name="adress" value="<?= $connectedUser->getAdress(); ?>" placeholder="Adresse">
        </div>
        <div class="form-group">
            <label for="zipCode">Code postal</label>
            <input type="text" class="form-control" id="zipCode" name="zipCode" value="<?= $connectedUser->getZipCode(); ?>" placeholder="Code postal">
        </div>
        <div class="form-group">
            <label for="city">Ville</label>
            <input type="text" class="form-control" id="city" name="city" value="<?= $connectedUser->getCity(); ?>" placeholder="Ville">
        </div>
        <button type="submit" class="btn btn-primary">Sauvegarder</button>
    </form>
    <?php if(isset($error) && !empty($error)) : ?>
        <ul class="alert alert-danger" role="alert">
            <?php foreach($error as $errorText): ?>
                <li>
                    <?= $errorText ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if(isset($success)) : ?>
        <div class="modal" id="modalMessage" tabindex="-1" role="dialog" aria-labelledby="modalMessageTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMessageTitle">Bienvenue sur Motiv'Online</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?= $success ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>