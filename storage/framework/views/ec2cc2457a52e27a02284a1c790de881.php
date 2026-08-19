<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Certificado de Conclusão</title>
    <style>
        /* Configuração A4 Paisagem sem margens nativas */
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'DejaVu Sans', sans-serif;
            background-color: #ffffff;
        }

        /* Moldura Externa */
        .outer-border {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 4px solid #1e3a8a;
        }


        /* Marca d'Água Centralizada */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 600px;
            height: 600px;
            margin-left: -300px;
            margin-top: -350px;
            opacity: 0.07;
            z-index: 0;
        }

        .top-left {
            top: 5px;
            left: 5px;
            border-right: none;
            border-bottom: none;
        }

        .top-right {
            top: 5px;
            right: 5px;
            border-left: none;
            border-bottom: none;
        }

        .bottom-left {
            bottom: 5px;
            left: 5px;
            border-right: none;
            border-top: none;
        }

        .bottom-right {
            bottom: 5px;
            right: 5px;
            border-left: none;
            border-top: none;
        }

        /* Conteúdo Principal Centralizado */
        .main-content {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-top: -200px;
            margin-left: -400px;
            width: 800px;
            text-align: center;
            z-index: 1;
        }

        /* Títulos */
        .title {
            font-size: 32px;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin: 0;
        }

        .subtitle {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #64748b;
            margin-top: 2px;
            margin-bottom: 18px;
        }

        /* Textos */
        .certify-text {
            font-size: 14px;
            color: #475569;
            margin: 0;
        }

        /* Nome do Aluno */
        .name {
            font-size: 28px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 10px auto 14px auto;
            padding-bottom: 4px;
            border-bottom: 2px solid #ca8a04;
            display: inline-block;
            min-width: 450px;
            max-width: 700px;
        }

        /* Descrição do Curso */
        .course-text {
            font-size: 14px;
            color: #334155;
            width: 90%;
            margin: 0 auto;
            line-height: 1.5;
        }

        .course-name {
            color: #0f172a;
            font-weight: bold;
            font-size: 18px;
        }

        /* Rodapé fixado na parte inferior */
        .footer-table {
            position: absolute;
            bottom: 25px;
            left: 30px;
            right: 30px;
            width: 92%;
            border-collapse: collapse;
            z-index: 10;
        }

        .footer-table td {
            vertical-align: bottom;
        }

        .signature-block {
            text-align: center;
            width: 220px;
            margin: 0 auto;
        }

        .signature-container {
            height: 45px;
            margin-bottom: 2px;
            text-align: center;
        }

        .signature-img {
            max-height: 45px;
            max-width: 180px;
        }

        .signature-line {
            border-top: 1px solid #94a3b8;
            margin-bottom: 4px;
        }

        .signature-title {
            font-size: 11px;
            font-weight: bold;
            color: #334155;
        }

        .signature-sub {
            font-size: 9px;
            color: #64748b;
        }

        .issue-date {
            font-size: 11px;
            color: #475569;
            text-align: left;
            line-height: 1.5;
        }
    </style>
</head>

<body>

    <div class="outer-border">
        <div class="inner-border">

            <div class="corner-decoration top-left"></div>
            <div class="corner-decoration top-right"></div>
            <div class="corner-decoration bottom-left"></div>
            <div class="corner-decoration bottom-right"></div>

            <!-- Marca d'Água -->
            <?php
            $watermarkPath = public_path('images/gearup-logo.png');
            $watermarkBase64 = file_exists($watermarkPath)
            ? 'data:image/png;base64,' . base64_encode(file_get_contents($watermarkPath))
            : null;
            ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($watermarkBase64): ?>
            <img src="<?php echo e($watermarkBase64); ?>" class="watermark" alt="Marca d'água">
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Conteúdo Central -->
            <div class="main-content">
                <div>
                    <h1 class="title">Certificado</h1>
                    <div class="subtitle">de Conclusão</div>
                </div>

                <div>
                    <p class="certify-text">Certificamos com honra que</p>

                    <!-- NOME DO ALUNO -->
                    <div class="name">
                        <?php echo e($certificado->usuario->nome ?? $certificado->usuario->name ?? $certificado->nome_aluno ?? 'Nome do Aluno'); ?>

                    </div>

                    <!-- NOME DO CURSO -->
                    <p class="course-text">
                        concluiu com êxito e excelente desempenho o curso de<br>
                        <strong class="course-name">
                            <?php echo e($certificado->curso->nome ?? $certificado->curso->titulo ?? $certificado->nome_curso ?? 'Nome do Curso'); ?>

                        </strong>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($certificado->curso->carga_horaria)): ?>
                        com carga horária de <strong><?php echo e($certificado->curso->carga_horaria); ?> horas</strong>.
                        <?php else: ?>
                        .
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- RODAPÉ DE DATAS E ASSINATURAS -->
            <table class="footer-table">
                <tr>
                    <!-- COLUNA 1: DATAS DE CONCLUSÃO E EMISSÃO -->
                    <td style="width: 34%;">
                        <div class="issue-date">
                            <?php
                            $dtConclusao = $certificado->data_conclusao ?? null;
                            $dtEmissao = $certificado->data_emissao ?? $certificado->created_at ?? now();
                            ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dtConclusao): ?>
                            <strong>Conclusão:</strong> <?php echo e(\Carbon\Carbon::parse($dtConclusao)->format('d/m/Y')); ?><br>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <strong>Emissão:</strong>
                            <?php echo e(\Carbon\Carbon::parse($dtEmissao)->locale('pt_BR')->translatedFormat('d \d\e F \d\e Y')); ?>

                        </div>
                    </td>

                    <!-- COLUNA 2: ASSINATURA DO PROFESSOR -->
                    <td style="width: 33%;">
                        <div class="signature-block">
                            <div class="signature-container">
                                
                                <?php
                                $profAbsPath = public_path('images/assinatura-professor.png');
                                $profBase64 = file_exists($profAbsPath)
                                ? 'data:image/png;base64,' . base64_encode(file_get_contents($profAbsPath))
                                : null;
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($profBase64): ?>
                                <img src="<?php echo e($profBase64); ?>" class="signature-img" alt="Assinatura Professor">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="signature-line"></div>

                            <!-- Exibe o nome que está na coluna 'instrutor' do curso -->
                            <div class="signature-title">
                                <?php echo e($certificado->curso->instrutor ?? 'Prof. Responsável'); ?>

                            </div>
                            <div class="signature-sub">Instrutor(a) do Curso</div>
                        </div>
                    </td>

                    <!-- COLUNA 3: ASSINATURA DO COORDENADOR -->
                    <td style="width: 33%;">
                        <div class="signature-block">
                            <div class="signature-container">
                                <?php
                                $coordPathRel = $coordenador_assinatura_path ?? 'images/assinatura-coordenador.png';
                                $coordAbsPath = public_path(ltrim($coordPathRel, '/'));
                                $coordBase64 = file_exists($coordAbsPath)
                                ? 'data:image/png;base64,' . base64_encode(file_get_contents($coordAbsPath))
                                : null;
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($coordBase64): ?>
                                <img src="<?php echo e($coordBase64); ?>" class="signature-img" alt="Assinatura Coordenador">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="signature-line"></div>
                            <div class="signature-title">Coordenador do Curso</div>
                            <div class="signature-sub">GearUp Tech</div>
                        </div>
                    </td>
                </tr>
            </table>

        </div>
    </div>

</body>

</html><?php /**PATH C:\Users\otvoa\Downloads\GearUp-corrigido (1)\GearUp-corrigido (1)\GearUp-main\resources\views/pdf/certificado.blade.php ENDPATH**/ ?>