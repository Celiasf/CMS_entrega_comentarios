<h3>
    <a href="<?php echo $_SESSION['home'] ?>" title="Inicio">Inicio</a> <span>| Comentarios </span>
</h3>
<div class="col m12 l6">
    <div class="row">
        <?php $id = ($datos->id) ? $datos->id : "nuevo" ?>
        <form action="" method="POST" class="col s12" enctype="multipart/form-data">
            <div class="input-field col s12">
                <input type="text" placeholder=" " name="nick">
                <label for="nick">Nick</label>
            </div>
            <div class="input-field col s12">
                <textarea id="comentario" class="materialize-textarea" name="comentario" placeholder="Escribe aquí tu comentario"></textarea>
                <label for="comentario">Comentario</label>
            </div>
            <div class="input-field col s12">
                <label for="comentario">Seleccione, si quiere, una foto:</label>
                <br><br>
                <div class="file-field input-field">
                    <div class="btn">
                        <span>Imagen</span>
                        <input type="file" name="imagen">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <button class="btn waves-effect waves-light" type="submit" name="comentar">
                            Comentar
                        </button>
                    </div>
                </div>

            </div>
    </div>

</div>
</form>
<div class="row">
    <?php foreach ($datos as $row){ ?>
        <article class="col m12 l6">
            <div class="card horizontal small">
                <div class="card-image">
                    <img src="<?php echo $_SESSION['public']."img/".$row->imagen ?>" alt="<?php echo $datos->nick ?>">
                </div>
                <div class="card-stacked">
                    <div class="card-content">
                        <h4><?php echo $row->nick ?></h4>
                        <p><?php echo $row->comentario ?></p>
                    </div>
                </div>
            </div>
        </article>
    <?php } ?>
</div>

