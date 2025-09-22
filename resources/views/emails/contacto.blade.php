<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Proyecto de Contacto</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 30px;
        }
        .info-grid {
            display: grid;
            gap: 20px;
        }
        .info-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .info-label {
            font-weight: 600;
            color: #007bff;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 16px;
            color: #333;
            word-wrap: break-word;
        }
        .description {
            background: #fff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #6c757d;
        }
        .badge {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }
            .content {
                padding: 20px;
            }
            .header {
                padding: 20px;
            }
            .header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 Nuevo Proyecto de Contacto</h1>
            <p>Recibido el {{ $fecha }}</p>
        </div>

        <div class="content">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">👤 Cliente</div>
                    <div class="info-value">{{ $nombre }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">🏢 Empresa/Organización</div>
                    <div class="info-value">{{ $empresa }}</div>
                </div>

                <div class="info-item">
                    <div class="info-label">📧 Email</div>
                    <div class="info-value">
                        <a href="mailto:{{ $email }}" style="color: #007bff; text-decoration: none;">{{ $email }}</a>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">📱 WhatsApp</div>
                    <div class="info-value">
                        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $whatsapp) }}"
                           style="color: #25d366; text-decoration: none;" target="_blank">{{ $whatsapp }}</a>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">🎯 Tipo de Proyecto</div>
                    <div class="info-value">
                        {{ $tipo_proyecto }}
                        <span class="badge">{{ ucfirst($tipo_proyecto) }}</span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">💰 Presupuesto</div>
                    <div class="info-value">{{ $presupuesto }}</div>
                </div>
            </div>

            <div class="description">
                <div class="info-label">📝 Descripción del Proyecto</div>
                <div class="info-value" style="margin-top: 10px; line-height: 1.8;">
                    {{ $descripcion }}
                </div>
            </div>
        </div>

        <div class="footer">
            <p><strong>MY Tech Solutions</strong></p>
            <p>Este email fue enviado desde el formulario de contacto de mytechsolutionsco.com</p>
            <p>
                <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $whatsapp) }}"
                   style="color: #25d366; text-decoration: none;">📱 Responder por WhatsApp</a> |
                <a href="mailto:{{ $email }}"
                   style="color: #007bff; text-decoration: none;">📧 Responder por Email</a>
            </p>
        </div>
    </div>
</body>
</html>