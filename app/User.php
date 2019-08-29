<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    
    protected $table = 'users';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'sobrenome',
        'tel_fixo',
        'tel_celular',
        'cpf'
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendPasswordResetNotification($token)
    {
        // Não esquece: use App\Notifications\ResetPassword;
        $this->notify(new ResetPasswordNotification($token));
    }

    public static function teste($email) {
        dd($email);
    }

    /**
    * Busca todos os usuários por página.
    * Temos que colocar o atributo page na requisição para
    * o próprio Laravel faça o tratamento da página que será carregada.
    * @param $quantidadePorPagina {int} Quantidade de registros por página.
    * @return array de Endereços
    */
    public static function buscaTodosUsuariosPorPagina(int $quantidadePorPagina) {
        return User::orderBy('name', 'asc')
                        ->select((new User)->getFillable())
                        ->addSelect('id')
                        ->paginate($quantidadePorPagina);
    }

    public static function buscarUsuarioPorId(int $id) {
        return DB::table('users')
                    ->where('id', '=', $id)
                    ->select((new User)->getFillable())
                    ->addSelect('id')
                    ->first();
    }
}
