Hola <i>{{ $demo->receiver }}</i>,
<p>Este es un correo de restauración de contraseña.</p>
<p>Por favor, haga click en el link de abajo para recuperar su contraseña.</p>
<div>
<p><b>Link de restauración:</b>&nbsp;{{ $demo->link }}</p>
</div>

Gracias,
<br/>
<i>{{ $demo->sender }}</i>