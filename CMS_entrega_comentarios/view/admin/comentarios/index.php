<h3>
    <a href="<?php echo $_SESSION['home'] ?>admin" title="Inicio">Inicio</a> <span>| Comentarios</span>
</h3>
<div class="row">
    <?php foreach ($datos as $row){ ?>
        <article class="col s12 l6">
            <div class="card horizontal  sticky-action admin">
                <div class="card-stacked">
                    <div class="card-content">
                        <div class="card-image">
                            <img src="<?php echo $_SESSION['public']."img/".$row->imagen ?>" alt="<?php echo $row->titulo ?>">
                        </div>
                        <h4>
                            <?php echo $row->nick ?>
                        </h4>
                        <strong>Comentario: </strong><?php echo $row->comentario ?><br>
                    </div>
                    <div class="card-action">
                        <?php $title = ($row->activo == 1) ? "Desactivar" : "Activar" ?>
                        <?php $color = ($row->activo == 1) ? "green-text" : "red-text" ?>
                        <?php $icono = ($row->activo == 1) ? "mood" : "mood_bad" ?>
                        <a href="<?php echo $_SESSION['home']."admin/comentarios/activar/".$row->id ?>" title="<?php echo $title ?>">
                            <i class="<?php echo $color ?> material-icons"><?php echo $icono ?></i>
                        </a>
                        <a href="#" class="activator" title="Borrar">
                            <i class="material-icons">delete</i>
                        </a>
                    </div>
                </div>
                <!--Confirmación de borrar-->
                <div class="card-reveal">
                    <span class="card-title grey-text text-darken-4">Borrar comentario<i class="material-icons right">close</i></span>
                    <p>
                        ¿Está seguro de que quiere borrar el comentario<strong><?php echo $row->comentarios?></strong>?<br>
                        Esta acción no se puede deshacer.
                    </p>
                    <a href="<?php echo $_SESSION['home']."admin/comentarios/borrar/".$row->id ?>" title="Borrar">
                        <button class="btn waves-effect waves-light" type="button">Borrar
                            <i class="material-icons right">delete</i>
                        </button>
                    </a>
                </div>
            </div>
        </article>
    <?php } ?>
</div>

