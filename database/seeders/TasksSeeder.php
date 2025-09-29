<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Participante;

class TasksSeeder extends Seeder
{
    public function run()
    {
        // 1. Insertar Áreas
        DB::table('areas')->insert([
            ['id_area' => 1, 'nombre_area' => 'Desarrollo de Software'],
            ['id_area' => 2, 'nombre_area' => 'Marketing Digital'],
            ['id_area' => 3, 'nombre_area' => 'Soporte TI'],
        ]);

        // 2. Insertar Cargos
        DB::table('cargos')->insert([
            // Cargos del Área de Desarrollo de Software
            ['id_cargo' => 1, 'nombre_cargo' => 'Desarrollador Web Full Stack'],
            ['id_cargo' => 2, 'nombre_cargo' => 'Desarrollador Frontend'],
            ['id_cargo' => 3, 'nombre_cargo' => 'Desarrollador Backend'],
            ['id_cargo' => 4, 'nombre_cargo' => 'Ingeniero de IA'],
            ['id_cargo' => 5, 'nombre_cargo' => 'Desarrollador Móvil'],
            ['id_cargo' => 6, 'nombre_cargo' => 'Desarrollador iOS'],
            ['id_cargo' => 7, 'nombre_cargo' => 'Desarrollador Android'],
            
            // Cargos del Área de Marketing
            ['id_cargo' => 8, 'nombre_cargo' => 'Especialista en Marketing Digital'],
            ['id_cargo' => 9, 'nombre_cargo' => 'Analista de Datos de Marketing'],
            ['id_cargo' => 10, 'nombre_cargo' => 'Gestor de Redes Sociales'],
            
            // Cargos del Área de Soporte
            ['id_cargo' => 11, 'nombre_cargo' => 'Técnico de Soporte'],
            ['id_cargo' => 12, 'nombre_cargo' => 'Analista de Sistemas'],
            ['id_cargo' => 13, 'nombre_cargo' => 'Especialista en Mantenimiento']
        ]);

        // 3. Insertar Clientes
        DB::table('clientes')->insert([
            [
                'id_cliente' => 1,
                'nombre_cliente' => 'TechCorp Solutions',
                'ruc' => '20123456789',
                'dirección' => 'Av. Principal 123, Lima',
                'correo_contacto' => 'contacto@techcorp.com',
                'teléfono' => '+51 999888777'
            ],
            [
                'id_cliente' => 2,
                'nombre_cliente' => 'InnovateAI Inc',
                'ruc' => '20987654321',
                'dirección' => 'Jr. Innovación 456, Arequipa',
                'correo_contacto' => 'info@innovateai.com',
                'teléfono' => '+51 888777666'
            ],
            [
                'id_cliente' => 3,
                'nombre_cliente' => 'Digital Marketing Pro',
                'ruc' => '20555666777',
                'dirección' => 'Calle Marketing 789, Cusco',
                'correo_contacto' => 'hello@dmpro.com',
                'teléfono' => '+51 777666555'
            ]
        ]);

        // 4. Los participantes ahora son manejados por ParticipantesSeeder

        // 5. Insertar Grupos
        DB::table('grupos')->insert([
            // Grupos del Área de Desarrollo de Software
            [
                'id_grupo' => 1,
                'nombre_grupo' => 'Programación Web',
                'descripcion' => 'Equipo dedicado al desarrollo de aplicaciones y plataformas web utilizando tecnologías modernas.',
                'codigo_clave' => 'WEB-DEV-2024',
                'fecha_creacion' => Carbon::now()->subDays(30),
                'creado_por' => 1,
                'id_area' => 1
            ],
            [
                'id_grupo' => 2,
                'nombre_grupo' => 'Inteligencia Artificial',
                'descripcion' => 'Equipo especializado en desarrollo de soluciones basadas en IA y machine learning.',
                'codigo_clave' => 'AI-DEV-2024',
                'fecha_creacion' => Carbon::now()->subDays(25),
                'creado_por' => 1,
                'id_area' => 1
            ],
            [
                'id_grupo' => 3,
                'nombre_grupo' => 'Programación Móvil',
                'descripcion' => 'Equipo enfocado en el desarrollo de aplicaciones móviles nativas y multiplataforma.',
                'codigo_clave' => 'MOB-DEV-2024',
                'fecha_creacion' => Carbon::now()->subDays(22),
                'creado_por' => 1,
                'id_area' => 1
            ],
            // Grupo del Área de Marketing
            [
                'id_grupo' => 4,
                'nombre_grupo' => 'Marketing Digital',
                'descripcion' => 'Equipo responsable de estrategias de marketing digital y análisis de datos.',
                'codigo_clave' => 'MKT-DIG-2024',
                'fecha_creacion' => Carbon::now()->subDays(20),
                'creado_por' => 1,
                'id_area' => 2
            ],
            // Grupo del Área de Soporte
            [
                'id_grupo' => 5,
                'nombre_grupo' => 'Soporte Técnico',
                'descripcion' => 'Equipo encargado del mantenimiento y soporte de sistemas en producción.',
                'codigo_clave' => 'SUPPORT-2024',
                'fecha_creacion' => Carbon::now()->subDays(15),
                'creado_por' => 1,
                'id_area' => 3
            ]
        ]);

        // 6. Insertar Proyectos
        DB::table('proyectos')->insert([
            // Proyecto de Programación Web
            [
                'id_proyecto' => 1,
                'nombre_proyecto' => 'Portal Empresarial TechCorp',
                'descripción' => 'Desarrollo de portal empresarial moderno con módulos de gestión, CRM y analítica.',
                'prioridad' => 'alto',
                'fecha_inicio' => Carbon::now()->subDays(30),
                'fecha_fin' => Carbon::now()->addDays(60),
                'Estado' => 'activo',
                'id_cliente' => 1,
                'id_grupo' => 1
            ],
            // Proyecto de Inteligencia Artificial
            [
                'id_proyecto' => 2,
                'nombre_proyecto' => 'Sistema de IA Predictivo',
                'descripción' => 'Plataforma de predicción de comportamiento de usuarios y recomendaciones personalizadas.',
                'prioridad' => 'alto',
                'fecha_inicio' => Carbon::now()->subDays(25),
                'fecha_fin' => Carbon::now()->addDays(45),
                'Estado' => 'activo',
                'id_cliente' => 2,
                'id_grupo' => 2
            ],
            // Proyecto de Programación Móvil
            [
                'id_proyecto' => 3,
                'nombre_proyecto' => 'App Móvil Corporativa',
                'descripción' => 'Aplicación móvil multiplataforma para gestión empresarial en tiempo real.',
                'prioridad' => 'alto',
                'fecha_inicio' => Carbon::now()->subDays(22),
                'fecha_fin' => Carbon::now()->addDays(50),
                'Estado' => 'activo',
                'id_cliente' => 1,
                'id_grupo' => 3
            ],
            // Proyecto de Marketing Digital
            [
                'id_proyecto' => 4,
                'nombre_proyecto' => 'Campaña Digital Integral',
                'descripción' => 'Estrategia y automatización de marketing digital multiplataforma.',
                'prioridad' => 'medio',
                'fecha_inicio' => Carbon::now()->subDays(20),
                'fecha_fin' => Carbon::now()->addDays(40),
                'Estado' => 'activo',
                'id_cliente' => 3,
                'id_grupo' => 4
            ],
            // Proyecto de Soporte Técnico
            [
                'id_proyecto' => 5,
                'nombre_proyecto' => 'Mantenimiento y Soporte',
                'descripción' => 'Servicios de soporte técnico y mantenimiento continuo de plataformas.',
                'prioridad' => 'alto',
                'fecha_inicio' => Carbon::now()->subDays(15),
                'fecha_fin' => Carbon::now()->addDays(90),
                'Estado' => 'activo',
                'id_cliente' => 1,
                'id_grupo' => 5
            ]
        ]);

        // 7. Insertar Participante_Proyecto (asignaciones)
        DB::table('participante_proyecto')->insert([
            // Programación Web
            ['id_participante_proyecto' => 1, 'id_participante' => 1, 'id_proyecto' => 1, 'rol_en_proyecto' => 'Full Stack Developer', 'fecha_asignacion' => Carbon::now()->subDays(30)],
            
            // Inteligencia Artificial
            ['id_participante_proyecto' => 2, 'id_participante' => 1, 'id_proyecto' => 2, 'rol_en_proyecto' => 'ML Engineer', 'fecha_asignacion' => Carbon::now()->subDays(25)],
            
            // Programación Móvil
            ['id_participante_proyecto' => 3, 'id_participante' => 1, 'id_proyecto' => 3, 'rol_en_proyecto' => 'Mobile Developer', 'fecha_asignacion' => Carbon::now()->subDays(22)],
            
            // Marketing Digital
            ['id_participante_proyecto' => 4, 'id_participante' => 1, 'id_proyecto' => 4, 'rol_en_proyecto' => 'Digital Marketer', 'fecha_asignacion' => Carbon::now()->subDays(20)],
            
            // Soporte Técnico
            ['id_participante_proyecto' => 5, 'id_participante' => 1, 'id_proyecto' => 5, 'rol_en_proyecto' => 'Support Engineer', 'fecha_asignacion' => Carbon::now()->subDays(15)],
        ]);

        // 8. Insertar Tareas
        DB::table('tareas')->insert([
            // Tareas de Programación Web
            [
                'id_tarea' => 1,
                'id_proyecto' => 1,
                'nombre_tarea' => 'Desarrollo Frontend del Portal',
                'descripción' => 'Implementar la interfaz de usuario del portal empresarial usando React y Material-UI.',
                'estado' => 'pendiente',
                'fecha_asignación' => Carbon::now()->subDays(5)->setHour(9)->setMinute(0),
                'fecha_inicio_asignada' => Carbon::now()->subDays(5)->setHour(14)->setMinute(0), // Debe comenzar a las 2PM
                'fecha_fin_asignada' => Carbon::now()->subDays(5)->setHour(17)->setMinute(0),    // Debe terminar a las 5PM
                'fecha_inicio' => Carbon::now()->subDays(5)->setHour(14)->setMinute(15),         // Comenzó a las 2:15PM
                'fecha_fin' => Carbon::now()->subDays(5)->setHour(16)->setMinute(45),            // Terminó a las 4:45PM
                'grupo_fecha_inicio' => Carbon::now()->subDays(5)->setHour(14)->setMinute(0),    // Inicio del grupo de tareas
                'grupo_fecha_fin' => Carbon::now()->subDays(5)->setHour(17)->setMinute(0),       // Fin del grupo de tareas
                'participante_id' => 1,
                'prioridad' => 'alto',
                'estado_asistencia' => null // Se calculará basado en las evidencias
            ],
            [
                'id_tarea' => 2,
                'id_proyecto' => 1,
                'nombre_tarea' => 'API REST del Portal',
                'descripción' => 'Desarrollar los endpoints de la API REST para el portal empresarial usando Laravel.',
                'estado' => 'ausente',
                'fecha_asignación' => Carbon::now()->subDays(3)->setHour(14)->setMinute(0),
                'fecha_inicio_asignada' => Carbon::now()->subDays(3)->setHour(14)->setMinute(0), // Debe comenzar a las 2PM
                'fecha_fin_asignada' => Carbon::now()->subDays(3)->setHour(17)->setMinute(0),    // Debe terminar a las 5PM
                'fecha_inicio' => null, // No tiene evidencias aún
                'fecha_fin' => null,
                'grupo_fecha_inicio' => Carbon::now()->subDays(3)->setHour(14)->setMinute(0),    // Inicio del grupo de tareas
                'grupo_fecha_fin' => Carbon::now()->subDays(3)->setHour(17)->setMinute(0),       // Fin del grupo de tareas
                'participante_id' => 1,
                'prioridad' => 'alto',
                'estado_asistencia' => null // Sin estado de asistencia porque no hay evidencias
            ],
            [
                'id_tarea' => 3,
                'id_proyecto' => 1,
                'nombre_tarea' => 'Setup de CI/CD Pipeline',
                'descripción' => 'Configurar pipeline de integración y despliegue continuo con Docker y GitHub Actions.',
                'estado' => 'ausente', // Cambiado a ausente ya que no tiene evidencias
                'fecha_asignación' => Carbon::now()->subDays(2)->setHour(9)->setMinute(0),
                'fecha_inicio_asignada' => Carbon::now()->subDays(2)->setHour(9)->setMinute(0),
                'fecha_fin_asignada' => Carbon::now()->subDays(2)->setHour(17)->setMinute(0),
                'fecha_inicio' => null, // No hay evidencias aún
                'fecha_fin' => null, // No hay evidencias aún
                'grupo_fecha_inicio' => Carbon::now()->subDays(2)->setHour(9)->setMinute(0),
                'grupo_fecha_fin' => Carbon::now()->subDays(2)->setHour(17)->setMinute(0),
                'participante_id' => 1,
                'prioridad' => 'medio',
                'estado_asistencia' => null // Null porque no hay evidencias
            ],

            // Tareas de Inteligencia Artificial
            [
                'id_tarea' => 4,
                'id_proyecto' => 2,
                'nombre_tarea' => 'Modelo de Predicción de Usuarios',
                'descripción' => 'Desarrollar y entrenar modelo de machine learning para predicción de comportamiento de usuarios.',
                'estado' => 'ausente',
                'fecha_asignación' => Carbon::now()->subDays(1)->setHour(10)->setMinute(0),
                'fecha_inicio_asignada' => Carbon::now()->subDays(1)->setHour(10)->setMinute(0),
                'fecha_fin_asignada' => Carbon::now()->subDays(1)->setHour(17)->setMinute(0),
                'fecha_inicio' => null, // No tiene evidencias aún
                'fecha_fin' => null,
                'grupo_fecha_inicio' => Carbon::now()->subDays(1)->setHour(10)->setMinute(0),
                'grupo_fecha_fin' => Carbon::now()->subDays(1)->setHour(17)->setMinute(0),
                'participante_id' => 1,
                'prioridad' => 'alto',
                'estado_asistencia' => null
            ],
            [
                'id_tarea' => 5,
                'id_proyecto' => 2,
                'nombre_tarea' => 'Optimización de Algoritmos de Recomendación',
                'descripción' => 'Optimizar rendimiento de algoritmos de recomendación para manejar datasets de gran escala.',
                'estado' => 'pendiente',
                'fecha_asignación' => Carbon::now()->subDays(4)->setHour(14)->setMinute(0),
                'fecha_inicio_asignada' => Carbon::now()->subDays(4)->setHour(14)->setMinute(0),
                'fecha_fin_asignada' => Carbon::now()->subDays(4)->setHour(17)->setMinute(0),
                'fecha_inicio' => Carbon::now()->subDays(4)->setHour(14)->setMinute(30),
                'fecha_fin' => Carbon::now()->subDays(4)->setHour(17)->setMinute(15),
                'grupo_fecha_inicio' => Carbon::now()->subDays(4)->setHour(14)->setMinute(0),
                'grupo_fecha_fin' => Carbon::now()->subDays(4)->setHour(17)->setMinute(0),
                'participante_id' => 1,
                'prioridad' => 'medio',
                'estado_asistencia' => 'registro salida tardía' // Terminó después del horario
            ],

            // Tareas de Programación Móvil
            [
                'id_tarea' => 6,
                'id_proyecto' => 3,
                'nombre_tarea' => 'Desarrollo App iOS',
                'descripción' => 'Implementar la versión iOS de la aplicación móvil corporativa usando Swift.',
                'estado' => 'pendiente',
                'fecha_asignación' => Carbon::now()->subDays(3)->setHour(9)->setMinute(0),
                'fecha_inicio_asignada' => Carbon::now()->subDays(3)->setHour(9)->setMinute(0),
                'fecha_fin_asignada' => Carbon::now()->subDays(3)->setHour(17)->setMinute(0),
                'fecha_inicio' => Carbon::now()->subDays(3)->setHour(9)->setMinute(15),
                'fecha_fin' => Carbon::now()->subDays(3)->setHour(15)->setMinute(30),
                'grupo_fecha_inicio' => Carbon::now()->subDays(3)->setHour(9)->setMinute(0),
                'grupo_fecha_fin' => Carbon::now()->subDays(3)->setHour(17)->setMinute(0),
                'participante_id' => 2,
                'prioridad' => 'alto',
                'estado_asistencia' => 'registro salida anticipada'
            ],
            [
                'id_tarea' => 7,
                'id_proyecto' => 1,
                'nombre_tarea' => 'Sistema de Notificaciones en Tiempo Real',
                'descripción' => 'Implementar WebSockets para notificaciones push y actualizaciones en tiempo real.',
                'estado' => 'incompleto', // Admin rechazó las evidencias
                'fecha_asignación' => Carbon::now()->subDays(2)->setHour(14)->setMinute(0),
                'fecha_inicio_asignada' => Carbon::now()->subDays(2)->setHour(14)->setMinute(0),
                'fecha_fin_asignada' => Carbon::now()->subDays(2)->setHour(17)->setMinute(0),
                'fecha_inicio' => Carbon::now()->subDays(2)->setHour(14)->setMinute(30),
                'fecha_fin' => Carbon::now()->subDays(2)->setHour(17)->setMinute(45),
                'grupo_fecha_inicio' => Carbon::now()->subDays(2)->setHour(14)->setMinute(0),
                'grupo_fecha_fin' => Carbon::now()->subDays(2)->setHour(17)->setMinute(0),
                'participante_id' => 2,
                'prioridad' => 'medio',
                'estado_asistencia' => 'incompleto'
            ],

            // Tareas de Marketing Digital
            [
                'id_tarea' => 8,
                'id_proyecto' => 4,
                'nombre_tarea' => 'Campaña en Redes Sociales',
                'descripción' => 'Planificar y ejecutar campaña integral en redes sociales para el nuevo producto.',
                'estado' => 'pendiente',
                'fecha_asignación' => Carbon::now()->subDays(1)->setHour(9)->setMinute(0),
                'fecha_inicio_asignada' => Carbon::now()->subDays(1)->setHour(9)->setMinute(0),
                'fecha_fin_asignada' => Carbon::now()->subDays(1)->setHour(17)->setMinute(0),
                'fecha_inicio' => Carbon::now()->subDays(1)->setHour(9)->setMinute(45),
                'fecha_fin' => Carbon::now()->subDays(1)->setHour(16)->setMinute(30),
                'grupo_fecha_inicio' => Carbon::now()->subDays(1)->setHour(9)->setMinute(0),
                'grupo_fecha_fin' => Carbon::now()->subDays(1)->setHour(17)->setMinute(0),
                'participante_id' => 3,
                'prioridad' => 'alto',
                'estado_asistencia' => 'registro salida anticipada'
            ],
            [
                'id_tarea' => 9,
                'id_proyecto' => 1,
                'nombre_tarea' => 'Módulo de Reportería Avanzada',
                'descripción' => 'Crear módulo frontend para generar reportes personalizados con filtros y exportación.',
                'estado' => 'ausente',
                'fecha_asignación' => Carbon::now()->setHour(9)->setMinute(0), // Asignada hoy
                'fecha_inicio_asignada' => Carbon::now()->setHour(9)->setMinute(0),
                'fecha_fin_asignada' => Carbon::now()->setHour(17)->setMinute(0),
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'grupo_fecha_inicio' => Carbon::now()->setHour(9)->setMinute(0),
                'grupo_fecha_fin' => Carbon::now()->setHour(17)->setMinute(0),
                'participante_id' => 3,
                'prioridad' => 'medio',
                'estado_asistencia' => null
            ]
        ]);

        // 9. Insertar algunas Evidencias de ejemplo
        DB::table('evidencias')->insert([
            [
                'id_evidencia' => 1,
                'id_tarea' => 1,
                'archivo' => 'evidencias/arquitectura_backend_v1.pdf',
                'tipo_archivo' => 'pdf',
                'fecha_subida' => Carbon::now()->subDays(24),
                'estado_validación' => 'aprobada',
                'observaciones_validacion' => 'Arquitectura bien definida'
            ],
            [
                'id_evidencia' => 2,
                'id_tarea' => 1,
                'archivo' => 'evidencias/diagrama_base_datos.pdf',
                'tipo_archivo' => 'pdf',
                'fecha_subida' => Carbon::now()->subDays(22),
                'estado_validación' => 'pendiente',
                'observaciones_validacion' => null
            ],
            [
                'id_evidencia' => 3,
                'id_tarea' => 6,
                'archivo' => 'evidencias/apis_inventario_final.docx',
                'tipo_archivo' => 'docx',
                'fecha_subida' => Carbon::now()->subDays(18),
                'estado_validación' => 'aprobada',
                'observaciones_validacion' => 'Implementación completa y funcional'
            ],
            [
                'id_evidencia' => 4,
                'id_tarea' => 8,
                'archivo' => 'evidencias/dashboard_mockups.pdf',
                'tipo_archivo' => 'pdf',
                'fecha_subida' => Carbon::now()->subDays(15),
                'estado_validación' => 'pendiente',
                'observaciones_validacion' => null
            ]
        ]);

        // 10. Insertar algunas Pausas de ejemplo
        DB::table('pausas')->insert([
            [
                'id_pausa' => 1,
                'tarea_id' => 2,
                'motivo' => 'Necesito revisar la documentación de JWT más a fondo para implementar correctamente la seguridad.',
                'hora' => Carbon::now()->subDays(18),
                'evidencia' => 'pausas/jwt_research_notes.pdf',
                'estado' => 'aprobada'
            ],
            [
                'id_pausa' => 2,
                'tarea_id' => 5,
                'motivo' => 'Problemas de performance en el servidor de desarrollo. Necesito tiempo adicional para optimizar.',
                'hora' => Carbon::now()->subDays(3),
                'evidencia' => null,
                'estado' => 'pendiente'
            ],
            [
                'id_pausa' => 3,
                'tarea_id' => 9,
                'motivo' => 'Esperando feedback del cliente sobre los requerimientos específicos del módulo de reportes.',
                'hora' => Carbon::now()->subDays(5),
                'evidencia' => 'pausas/email_cliente_reportes.pdf',
                'estado' => 'aprobada'
            ]
        ]);
    }
}