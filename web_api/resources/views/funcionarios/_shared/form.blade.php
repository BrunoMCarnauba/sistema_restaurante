@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@csrf

<!-- NOME -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <i class="fa fa-user"></i>
        </div>
        <input type="text" id="username" name="nome" value="{{old('nome', $funcionario->nome)}}" placeholder="Nome" class="form-control">
    </div>
</div>

<!-- EMAIL -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <i class="fa fa-envelope"></i>
        </div>
        <input type="email" id="email" name="email" value="{{old('email', $funcionario->email)}}" placeholder="Email" class="form-control">
    </div>
</div>

<!-- SENHA -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <i class="fa fa-asterisk"></i>
        </div>
        <input type="password" id="password" name="senha" placeholder="Senha" class="form-control">
    </div>
</div>

<!-- CARGO -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <i class="fas fa-briefcase"></i>
        </div>
        <select name="cargo" class="camposCadastro">
            <option value='Selecione'>Selecione um cargo</option>
            @foreach ($cargos as $cargo) <!-- Existem vários cargos, em cada repetição ele pega um cargo e cria a <option> com o nome dele -->
                    <option value="{{$cargo->id_cargo}}">{{$cargo->nome}}</option>
            @endforeach
        </select>
    </div>
<!--    <label for="nf-email" class=" form-control-label">Administrador</label>
    <select name="admin" id="select" class="form-control">
        <option value="0">Não</option>
        <option value="1" @if(old('nome', $funcionario->nome)) selected @endif>Sim</option>
    </select>-->
</div>
