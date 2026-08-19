<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Certificado de Conclusão</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Montserrat:wght@300;400;600;700&display=swap');

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: #e2e8f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Container Principal */
        .certificate-container {
            width: 900px;
            background: #ffffff;
            padding: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        /* Borda Externa (Azul Escuro) */
        .outer-border {
            border: 3px solid #0f172a;
            padding: 8px;
            position: relative;
        }

        /* Borda Interna (Dourada) */
        .inner-border {
            border: 2px solid #ca8a04;
            padding: 40px 50px;
            text-align: center;
            background: radial-gradient(circle at center, #ffffff 60%, #fdfbf7 100%);
            position: relative;
        }

        /* Detalhes Ornamentais nos Cantos */
        .corner-decoration {
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid #ca8a04;
        }
        .top-left { top: 4px; left: 4px; border-right: none; border-bottom: none; }
        .top-right { top: 4px; right: 4px; border-left: none; border-bottom: none; }
        .bottom-left { bottom: 4px; left: 4px; border-right: none; border-top: none; }
        .bottom-right { bottom: 4px; right: 4px; border-left: none; border-top: none; }

        /* Logo */
        .logo-container {
            margin-bottom: 25px;
        }

        .logo {
            max-width: 160px;
            max-height: 70px;
            object-fit: contain;
        }

        /* Título */
        .title {
            font-family: 'Cinzel', serif;
            font-size: 34px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 0 0 10px 0;
        }

        .subtitle {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #64748b;
            margin-bottom: 30px;
        }

        /* Texto de Concessão */
        .certify-text {
            font-size: 15px;
            color: #475569;
            margin: 0;
            font-weight: 300;
        }

        /* Nome do Aluno */
        .name {
            font-family: 'Cinzel', serif;
            font-size: 32px;
            font-weight: 700;
            color: #1e3a8a;
            margin: 15px 0 20px 0;
            padding-bottom: 10px;
            display: inline-block;
            border-bottom: 2px solid #ca8a04;
            min-width: 350px;
        }

        /* Descrição do Curso */
        .course-text {
            font-size: 15px;
            color: #334155;
            max-width: 650px;
            margin: 0 auto 40px auto;
            line-height: 1.6;
        }

        .course-name {
            color: #0f172a;
            font-weight: 700;
        }

        /* Rodapé / Assinaturas */
        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 40px;
            padding: 0 30px;
        }

        .signature-block {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            margin-bottom: 8px;
        }

        .signature-title {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
        }

        .signature-sub {
            font-size: 10px;
            color: #64748b;
        }

        .issue-date {
            font-size: 11px;
            color: #64748b;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="certificate-container">
        <div class="outer-border">
            <div class="inner-border">
                
                <!-- Cantos Decorativos -->
                <div class="corner-decoration top-left"></div>
                <div class="corner-decoration top-right"></div>
                <div class="corner-decoration bottom-left"></div>
                <div class="corner-decoration bottom-right"></div>

                <!-- Logo da Empresa/Plataforma -->
                <div class="logo-container">
                    <!-- IMPORTANTE: Substitua o atributo src pelo caminho real da sua logo -->
                    <img src="https://via.placeholder.com/180x60?text=SUA+LOGO+AQUI" alt="Logo da Empresa" class="logo">
                </div>

                <!-- Cabeçalho -->
                <h1 class="title">Certificado</h1>
                <div class="subtitle">de Conclusão</div>

                <!-- Conteúdo -->
                <p class="certify-text">Certificamos com honra que</p>
                <div class="name">Lucas Andrade</div>
                <p class="course-text">
                    concluiu com êxito e excelente desempenho o curso de<br>
                    <strong class="course-name">Docker e Kubernetes na Prática</strong>.
                </p>

                <!-- Rodapé / Assinaturas e Data -->
                <div class="footer">
                    <div class="issue-date">
                        <p style="margin: 0;">Emissão: 13 de Agosto de 2026</p>
                    </div>

                    <div class="signature-block">
                        <div class="signature-line"></div>
                        <div class="signature-title">Coordenador do Curso</div>
                        <div class="signature-sub">GearUp Tech</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>