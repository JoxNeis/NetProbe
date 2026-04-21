<section id="header">
    <h4>Header</h4>

    <?php
    require_once(__DIR__ . "/../../../src/Domain/ValueObject/HttpHeaderCategory.php");
    use ValueObject\HttpHeaderCategory;

    foreach (HttpHeaderCategory::cases() as $header):
        $name = $header->value;
        $id = strtolower(str_replace('-', '_', $name));
        ?>
        <div class="input-group">
            <label for="<?= $id ?>">
                <?= $name ?>
            </label>
            <input type="text" id="<?= $id ?>" name="headers[<?= $name ?>]" placeholder="<?= $name ?>">
        </div>
    <?php endforeach; ?>
</section>