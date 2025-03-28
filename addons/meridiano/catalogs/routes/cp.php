<?php

use Meridiano\Catalogs\Http\Controllers\CatalogController;
use Meridiano\Catalogs\Http\Controllers\CalculadoraController;
use Meridiano\Catalogs\Http\Controllers\Calculadora\ConfiguracionesController;
use Meridiano\Catalogs\Http\Controllers\Calculadora\PeriodicidadesController;
use Meridiano\Catalogs\Http\Controllers\Calculadora\ProfesionesController;
use Meridiano\Catalogs\Http\Controllers\Calculadora\SalariosController;
use Meridiano\Catalogs\Http\Controllers\Calculadora\VacacionesAnterioresController;
use Meridiano\Catalogs\Http\Controllers\Calculadora\VacacionesActualesController;
use Meridiano\Catalogs\Http\Controllers\ChatbotController;
use Meridiano\Catalogs\Http\Controllers\Chatbot\TopicosController;
use Meridiano\Catalogs\Http\Controllers\Chatbot\SubTopicosController;
use Meridiano\Catalogs\Http\Controllers\Chatbot\PreguntasController;
use Meridiano\Catalogs\Http\Controllers\Chatbot\ConfiguracionesController as ChatbotConfiguracionesController;
use Meridiano\Catalogs\Http\Controllers\Chatbot\ConversacionesController;
use Meridiano\Catalogs\Http\Controllers\ServerController;

Route::get('calalog', [CatalogController::class, 'index'])->name('catalog.index');

// Seccion calculadora
Route::get('calalog/calculadora', [CalculadoraController::class, 'index'])->name('catalog.calculadora.index');

Route::get('calalog/calculadora/periodicidades', [PeriodicidadesController::class, 'index'])->name('calculadora.periodicidades');
Route::get('calalog/calculadora/periodicidades/{id}/edit', [PeriodicidadesController::class, 'edit'])->name('calculadora.periodicidades.edit');
Route::get('calalog/calculadora/periodicidades/create', [PeriodicidadesController::class, 'create'])->name('calculadora.periodicidades.create');
Route::post('calalog/calculadora/periodicidades', [PeriodicidadesController::class, 'store'])->name('calculadora.periodicidades.store');
Route::get('calalog/calculadora/periodicidades/{id}/edit', [PeriodicidadesController::class, 'edit'])->name('calculadora.periodicidades.edit');
Route::put('calalog/calculadora/periodicidades/{id}', [PeriodicidadesController::class, 'update'])->name('calculadora.periodicidades.update');
Route::delete('/periodicidades/{id}', [PeriodicidadesController::class, 'destroy'])->name('calculadora.periodicidades.destroy');

Route::get('calalog/calculadora/profesiones', [ProfesionesController::class, 'index'])->name('calculadora.profesiones');
Route::get('calalog/calculadora/profesiones/{id}/edit', [ProfesionesController::class, 'edit'])->name('calculadora.profesiones.edit');
Route::get('calalog/calculadora/profesiones/create', [ProfesionesController::class, 'create'])->name('calculadora.profesiones.create');
Route::post('calalog/calculadora/profesiones', [ProfesionesController::class, 'store'])->name('calculadora.profesiones.store');
Route::get('calalog/calculadora/profesiones/{id}/edit', [ProfesionesController::class, 'edit'])->name('calculadora.profesiones.edit');
Route::put('calalog/calculadora/profesiones/{id}', [ProfesionesController::class, 'update'])->name('calculadora.profesiones.update');
Route::delete('/profesiones/{id}', [ProfesionesController::class, 'destroy'])->name('calculadora.profesiones.destroy');

Route::get('calalog/calculadora/vacaciones_anteriores', [VacacionesAnterioresController::class, 'index'])->name('calculadora.vacaciones_anteriores');
Route::get('/vacaciones_anteriores/create', [VacacionesAnterioresController::class, 'create'])->name('calculadora.vacaciones_anteriores.create');
Route::post('/vacaciones_anteriores', [VacacionesAnterioresController::class, 'store'])->name('calculadora.vacaciones_anteriores.store');
Route::get('/vacaciones_anteriores/{id}/edit', [VacacionesAnterioresController::class, 'edit'])->name('calculadora.vacaciones_anteriores.edit');
Route::put('/vacaciones_anteriores/{id}', [VacacionesAnterioresController::class, 'update'])->name('calculadora.vacaciones_anteriores.update');
Route::delete('/vacaciones_anteriores/{id}', [VacacionesAnterioresController::class, 'destroy'])->name('calculadora.vacaciones_anteriores.destroy');

Route::get('calalog/calculadora/vacaciones_actuales', [VacacionesActualesController::class, 'index'])->name('calculadora.vacaciones_actuales');
Route::get('/vacaciones_actuales/create', [VacacionesActualesController::class, 'create'])->name('calculadora.vacaciones_actuales.create');
Route::post('/vacaciones_actuales', [VacacionesActualesController::class, 'store'])->name('calculadora.vacaciones_actuales.store');
Route::get('/vacaciones_actuales/{id}/edit', [VacacionesActualesController::class, 'edit'])->name('calculadora.vacaciones_actuales.edit');
Route::put('/vacaciones_actuales/{id}', [VacacionesActualesController::class, 'update'])->name('calculadora.vacaciones_actuales.update');
Route::delete('/vacaciones_actuales/{id}', [VacacionesActualesController::class, 'destroy'])->name('calculadora.vacaciones_actuales.destroy');

Route::get('calalog/calculadora/configuraciones', [ConfiguracionesController::class, 'index'])->name('calculadora.configuraciones');
Route::get('/configuraciones/create', [ConfiguracionesController::class, 'create'])->name('calculadora.configuraciones.create');
Route::post('/configuraciones', [ConfiguracionesController::class, 'store'])->name('calculadora.configuraciones.store');
Route::get('/configuraciones/{id}/edit', [ConfiguracionesController::class, 'edit'])->name('calculadora.configuraciones.edit');
Route::put('/configuraciones/{id}', [ConfiguracionesController::class, 'update'])->name('calculadora.configuraciones.update');
Route::delete('/configuraciones/{id}', [ConfiguracionesController::class, 'destroy'])->name('calculadora.configuraciones.destroy');

Route::get('calalog/calculadora/salarios', [SalariosController::class, 'index'])->name('calculadora.salarios');
Route::get('calalog/calculadora/salarios/{id}/edit', [SalariosController::class, 'edit'])->name('calculadora.salarios.edit');
Route::get('calalog/calculadora/salarios/create', [SalariosController::class, 'create'])->name('calculadora.salarios.create');
Route::post('calalog/calculadora/salarios', [SalariosController::class, 'store'])->name('calculadora.salarios.store');
Route::get('calalog/calculadora/salarios/{id}/edit', [SalariosController::class, 'edit'])->name('calculadora.salarios.edit');
Route::put('calalog/calculadora/salarios/{id}', [SalariosController::class, 'update'])->name('calculadora.salarios.update');
Route::delete('/salarios/{id}', [SalariosController::class, 'destroy'])->name('calculadora.salarios.destroy');

// Seccion chatbot
Route::get('calalog/chatbot', [ChatbotController::class, 'index'])->name('catalog.chatbot.index');
Route::get('calalog/chatbot/clear-cache', [ChatbotController::class, 'clearCache'])->name('chatbot.cache');

Route::get('/topicos', [TopicosController::class, 'index'])->name('chatbot.topicos');
Route::get('/topicos/create', [TopicosController::class, 'create'])->name('chatbot.topicos.create');
Route::post('/topicos', [TopicosController::class, 'store'])->name('chatbot.topicos.store');
Route::get('/topicos/{id}/edit', [TopicosController::class, 'edit'])->name('chatbot.topicos.edit');
Route::put('/topicos/{id}', [TopicosController::class, 'update'])->name('chatbot.topicos.update');
Route::delete('/topicos/{id}', [TopicosController::class, 'destroy'])->name('chatbot.topicos.destroy');

Route::get('subtopicos', [SubTopicosController::class, 'index'])->name('chatbot.subtopicos');
Route::get('subtopicos/create', [SubTopicosController::class, 'create'])->name('chatbot.subtopicos.create');
Route::post('subtopicos', [SubTopicosController::class, 'store'])->name('chatbot.subtopicos.store');
Route::get('subtopicos/{id}/edit', [SubTopicosController::class, 'edit'])->name('chatbot.subtopicos.edit');
Route::put('subtopicos/{id}', [SubTopicosController::class, 'update'])->name('chatbot.subtopicos.update');
Route::delete('subtopicos/{id}', [SubTopicosController::class, 'destroy'])->name('chatbot.subtopicos.destroy');

Route::get('/preguntas', [PreguntasController::class, 'index'])->name('chatbot.preguntas');
Route::get('/preguntas/create', [PreguntasController::class, 'create'])->name('chatbot.preguntas.create');
Route::post('/preguntas', [PreguntasController::class, 'store'])->name('chatbot.preguntas.store');
Route::get('/preguntas/{id}/edit', [PreguntasController::class, 'edit'])->name('chatbot.preguntas.edit');
Route::put('/preguntas/{id}', [PreguntasController::class, 'update'])->name('chatbot.preguntas.update');
Route::delete('/preguntas/{id}', [PreguntasController::class, 'destroy'])->name('chatbot.preguntas.destroy');
Route::get('/preguntas/sugerencias', [PreguntasController::class, 'obtenerSugerenciasDinamicas'])->name('chatbot.preguntas.sugerencias');

Route::get('calalog/chatbot/configuraciones', [ChatbotConfiguracionesController::class, 'index'])->name('chatbot.configuraciones');
Route::get('chatbot/configuraciones/create', [ChatbotConfiguracionesController::class, 'create'])->name('chatbot.configuraciones.create');
Route::post('chatbot/configuraciones', [ChatbotConfiguracionesController::class, 'store'])->name('chatbot.configuraciones.store');
Route::get('chatbot/configuraciones/{id}/edit', [ChatbotConfiguracionesController::class, 'edit'])->name('chatbot.configuraciones.edit');
Route::put('chatbot/configuraciones/{id}', [ChatbotConfiguracionesController::class, 'update'])->name('chatbot.configuraciones.update');
Route::delete('chatbot/configuraciones/{id}', [ChatbotConfiguracionesController::class, 'destroy'])->name('chatbot.configuraciones.destroy');
Route::get('chatbot/configuraciones/clear-cache', [ChatbotConfiguracionesController::class, 'clearCache'])->name('chatbot.configuraciones.cache');

Route::get('calalog/chatbot/conversaciones', [ConversacionesController::class, 'index'])->name('chatbot.conversaciones');
Route::get('chatbot/configuraciones/{id}/view', [ConversacionesController::class, 'view'])->name('chatbot.conversaciones.view');
Route::get('chatbot/configuraciones/busqueda', [ConversacionesController::class, 'search'])->name('chatbot.conversaciones.search');
Route::get('chatbot/configuraciones/descargar', [ConversacionesController::class, 'download'])->name('chatbot.conversaciones.download');

Route::get('server', [ServerController::class, 'index'])->name('server.index');
Route::post('server/respaldo', [ServerController::class, 'backup'])->name('server.backup');
