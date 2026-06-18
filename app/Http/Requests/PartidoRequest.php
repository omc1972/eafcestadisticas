<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $aliases = [
            'local_id'                        => 'equipo_id',
            'posesion_local'                  => 'posesion_equipo',
            'posesion_visitante'              => 'posesion_rival',
            'delantera_local'                 => 'delantera_equipo',
            'delantera_visitante'             => 'delantera_rival',
            'media_local'                     => 'media_equipo',
            'media_visitante'                 => 'media_rival',
            'defensa_local'                   => 'defensa_equipo',
            'defensa_visitante'               => 'defensa_rival',
            'tiros_local'                     => 'tiros_equipo',
            'tiros_visitante'                 => 'tiros_rival',
            'tiros_a_puerta_local'            => 'tiros_a_puerta_equipo',
            'tiros_a_puerta_visitante'        => 'tiros_a_puerta_rival',
            'pases_local'                     => 'pases_equipo',
            'pases_visitante'                 => 'pases_rival',
            'porcentaje_pases_local'          => 'porcentaje_pases_equipo',
            'porcentaje_pases_visitante'      => 'porcentaje_pases_rival',
            'goles_local'                     => 'goles_equipo',
            'goles_visitante'                 => 'goles_rival',
            'valor_local'                     => 'valor_equipo',
            'valor_visitante'                 => 'valor_rival',
            'entradas_local'                  => 'entradas_equipo',
            'entradas_visitante'              => 'entradas_rival',
            'entradas_local_completadas'      => 'entradas_equipo_completadas',
            'entradas_visitante_completadas'  => 'entradas_rival_completadas',
            'distancia_local'                 => 'distancia_equipo',
            'distancia_visitante'             => 'distancia_rival',
            'faltas_local'                    => 'faltas_equipo',
            'faltas_visitante'                => 'faltas_rival',
            'penalties_local'                 => 'penalties_equipo',
            'penalties_visitante'             => 'penalties_rival',
            'tarjetas_amarillas_local'        => 'tarjetas_amarillas_equipo',
            'tarjetas_amarillas_visitante'    => 'tarjetas_amarillas_rival',
            'tarjetas_rojas_local'            => 'tarjetas_rojas_equipo',
            'tarjetas_rojas_visitante'        => 'tarjetas_rojas_rival',
            'corners_local'                   => 'corners_equipo',
            'corners_visitante'               => 'corners_rival',
            'intercepciones_local'            => 'interceciones_equipo',
            'intercepciones_visitante'        => 'intercepciones_rival',
            'balones_ganados_local'           => 'balones_ganados_equipo',
            'balones_ganados_visitante'       => 'balones_ganados_rival',
            'balones_perdidos_local'          => 'balones_perdidos_equipo',
            'balones_perdidos_visitante'      => 'balones_perdidos_rival',
        ];

        $merge = [];
        foreach ($aliases as $alias => $canonical) {
            if ($this->filled($alias) && !$this->filled($canonical)) {
                $merge[$canonical] = $this->input($alias);
            }
        }

        $eventos = $this->input('eventos', []);
        if (is_array($eventos)) {
            $merge['eventos'] = array_map(function ($evento) {
                if (is_array($evento) && array_key_exists('local_id', $evento) && !array_key_exists('equipo_id', $evento)) {
                    $evento['equipo_id'] = $evento['local_id'];
                }
                return $evento;
            }, $eventos);
        }

        foreach (['alineaciones', 'alineaciones_rival'] as $key) {
            $items = $this->input($key, []);
            if (is_array($items)) {
                $merge[$key] = array_map(function ($a) {
                    if (is_array($a) && array_key_exists('local_id', $a) && !array_key_exists('equipo_id', $a)) {
                        $a['equipo_id'] = $a['local_id'];
                    }
                    return $a;
                }, $items);
            }
        }

        if (!empty($merge)) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'partido_como_local'                    => 'required|boolean',
            'campeonato_id'                         => 'nullable|exists:campeonatos,id',
            'dificultad'                            => 'nullable|string|max:255',
            'jornada'                               => 'nullable|string|max:255',
            'equipo_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($this->boolean('local_es_rival')) {
                        if (!\App\Models\Rival::where('id', $value)->exists()) {
                            $fail('El local seleccionado no existe en rivales.');
                        }
                        return;
                    }
                    if (!\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail('El equipo local no existe.');
                    }
                },
            ],
            'visitante_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if (is_null($value) || $value === 0) return;
                    if ($this->boolean('visitante_es_rival')) {
                        if (!\App\Models\Rival::where('id', $value)->exists()) {
                            $fail('El visitante seleccionado no existe en rivales.');
                        }
                        return;
                    }
                    if (!\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail('El visitante seleccionado no existe en equipos.');
                    }
                },
            ],
            'visitante_es_rival'                    => 'required|boolean',
            'local_es_rival'                        => 'required|boolean',
            'rival_id'                              => 'nullable|exists:rivals,id',
            'temporada_id'                          => 'required|exists:temporadas,id',
            'competicion_id'                        => 'required|exists:competicions,id',
            'minutos_jugados'                       => 'nullable|integer',
            'puntuacion'                            => 'nullable|integer',
            'delantera_equipo'                      => 'nullable|numeric',
            'delantera_rival'                       => 'nullable|numeric',
            'media_equipo'                          => 'nullable|numeric',
            'media_rival'                           => 'nullable|numeric',
            'defensa_equipo'                        => 'nullable|numeric',
            'defensa_rival'                         => 'nullable|numeric',
            'posesion_equipo'                       => 'nullable|numeric',
            'posesion_rival'                        => 'nullable|numeric',
            'tiros_equipo'                          => 'nullable|integer',
            'tiros_rival'                           => 'nullable|integer',
            'tiros_a_puerta_equipo'                 => 'nullable|integer',
            'tiros_a_puerta_rival'                  => 'nullable|integer',
            'pases_equipo'                          => 'nullable|integer',
            'pases_rival'                           => 'nullable|integer',
            'porcentaje_pases_equipo'               => 'nullable|numeric',
            'porcentaje_pases_rival'                => 'nullable|numeric',
            'goles_equipo'                          => 'nullable|integer',
            'goles_rival'                           => 'nullable|integer',
            'valor_equipo'                          => 'nullable|integer',
            'valor_rival'                           => 'nullable|integer',
            'entradas_equipo'                       => 'nullable|integer',
            'entradas_rival'                        => 'nullable|integer',
            'entradas_equipo_completadas'           => 'nullable|integer',
            'entradas_rival_completadas'            => 'nullable|integer',
            'distancia_equipo'                      => 'nullable|numeric',
            'distancia_rival'                       => 'nullable|numeric',
            'faltas_equipo'                         => 'nullable|integer',
            'faltas_rival'                          => 'nullable|integer',
            'penalties_equipo'                      => 'nullable|integer',
            'penalties_rival'                       => 'nullable|integer',
            'tarjetas_amarillas_equipo'             => 'nullable|integer',
            'tarjetas_amarillas_rival'              => 'nullable|integer',
            'tarjetas_rojas_equipo'                 => 'nullable|integer',
            'tarjetas_rojas_rival'                  => 'nullable|integer',
            'corners_equipo'                        => 'nullable|integer',
            'corners_rival'                         => 'nullable|integer',
            'interceciones_equipo'                  => 'nullable|integer',
            'intercepciones_rival'                  => 'nullable|integer',
            'balones_ganados_equipo'                => 'nullable|integer',
            'balones_ganados_rival'                 => 'nullable|integer',
            'balones_perdidos_equipo'               => 'nullable|integer',
            'balones_perdidos_rival'                => 'nullable|integer',
            'alineaciones'                          => 'nullable|array',
            'alineaciones_rival'                    => 'nullable|array',
            'alineaciones.*.jugador_id'             => 'required|exists:jugadors,id',
            'alineaciones.*.equipo_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail($attribute . ' debe ser null, 0 o un id de equipo válido.');
                    }
                },
            ],
            'alineaciones.*.rival_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Rival::where('id', $value)->exists()) {
                        $fail($attribute . ' debe ser null, 0 o un id de rival válido.');
                    }
                },
            ],
            'alineaciones.*.tiros'                  => 'nullable|integer',
            'alineaciones.*.tiros_a_puerta'         => 'nullable|integer',
            'alineaciones.*.tiros_al_palo'          => 'nullable|integer',
            'alineaciones.*.pases'                  => 'nullable|integer',
            'alineaciones.*.pases_exitosos'         => 'nullable|integer',
            'alineaciones.*.entradas'               => 'nullable|integer',
            'alineaciones.*.entradas_exitosas'      => 'nullable|integer',
            'alineaciones.*.regates'                => 'nullable|integer',
            'alineaciones.*.regates_exitosos'       => 'nullable|integer',
            'alineaciones.*.posesion_ganada'        => 'nullable|integer',
            'alineaciones.*.posesion_perdida'       => 'nullable|integer',
            'alineaciones.*.fueras_de_juego'        => 'nullable|integer',
            'alineaciones.*.faltas_cometidas'       => 'nullable|integer',
            'alineaciones.*.faltas_recibidas'       => 'nullable|integer',
            'alineaciones.*.posesion'               => 'nullable|numeric',
            'alineaciones.*.distancia_recorrida'    => 'nullable|numeric',
            'alineaciones.*.rendimiento'            => 'nullable|numeric',
            'alineaciones.*.jugador_del_partido'    => 'nullable|boolean',
            'eventos'                               => 'nullable|array',
            'eventos.*.jugador_id'                  => 'required|exists:jugadors,id',
            'eventos.*.local_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail($attribute . ' debe ser null, 0 o un id de equipo local válido.');
                    }
                },
            ],
            'eventos.*.visitante_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (is_null($value) || $value === 0) return;
                    if ($this->boolean('visitante_es_rival')) {
                        if (!\App\Models\Rival::where('id', $value)->exists()) {
                            $fail($attribute . ' debe ser un id de rival válido.');
                        }
                        return;
                    }
                    if (!\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail($attribute . ' debe ser un id de equipo visitante válido.');
                    }
                },
            ],
            'eventos.*.equipo_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Equipo::where('id', $value)->exists()) {
                        $fail($attribute . ' debe ser null, 0 o un id de equipo válido.');
                    }
                },
            ],
            'eventos.*.rival_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!is_null($value) && $value !== 0 && !\App\Models\Rival::where('id', $value)->exists()) {
                        $fail($attribute . ' debe ser null, 0 o un id de rival válido.');
                    }
                },
            ],
            'eventos.*.minuto'                      => 'required|integer',
            'eventos.*.tipo_evento_id'              => 'required|exists:tipo_eventos,id',
        ];
    }
}
