<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificado de Conclusão</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Georgia', serif;
            margin: 0;
            padding: 0;
            background-color: #fcfbf9;
            color: #2c3e50;
        }
        .container {
            width: 297mm;
            height: 210mm;
            box-sizing: border-box;
            padding: 20mm;
            border: 15mm solid #1e293b; /* Borda externa azul petróleo */
            position: relative;
        }
        .inner-border {
            border: 1px solid #d4af37; /* Linha fina dourada */
            height: 100%;
            width: 100%;
            padding: 15mm 20mm;
            text-align: center;
            box-sizing: border-box;
        }
        .logo {
            font-family: sans-serif;
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 4px;
            color: #1e293b;
            margin-bottom: 8mm;
            text-transform: uppercase;
        }
        .title {
            font-size: 34pt;
            color: #1e293b;
            margin-bottom: 4mm;
        }
        .subtitle {
            font-family: sans-serif;
            font-size: 11pt;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #d4af37;
            margin-bottom: 12mm;
            font-weight: bold;
        }
        .statement {
            font-size: 14pt;
            line-height: 1.6;
            color: #4a5568;
            margin-bottom: 6mm;
        }
        .student-name {
            font-size: 26pt;
            color: #1e293b;
            border-bottom: 1px solid #e2e8f0;
            display: inline-block;
            padding-bottom: 2mm;
            margin-bottom: 8mm;
            font-weight: bold;
            width: 70%;
        }
        .course-name {
            font-size: 18pt;
            font-weight: bold;
            color: #1e293b;
            font-style: italic;
        }
        .details {
            font-family: sans-serif;
            font-size: 9.5pt;
            color: #718096;
            margin-bottom: 15mm;
        }
        /* Assinaturas organizadas usando tabela para compatibilidade total com Dompdf */
        .signatures-table {
            width: 100%;
            margin-top: 10mm;
            border-collapse: collapse;
        }
        .signature-cell {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }
        .signature-line {
            width: 60%;
            margin: 0 auto 5px auto;
            border-top: 1px solid #a0aec0;
        }
        .signature-title {
            font-family: sans-serif;
            font-size: 9pt;
            color: #4a5568;
            font-weight: bold;
            text-transform: uppercase;
        }
        .signature-subtitle {
            font-family: sans-serif;
            font-size: 8pt;
            color: #718096;
        }
        .auth-code {
            position: absolute;
            bottom: 4mm;
            right: 4mm;
            font-family: monospace;
            font-size: 7.5pt;
            color: #a0aec0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="inner-border">
            <div class="logo">GEARUP LMS</div>
            
            <div class="title">Certificado de Conclusão</div>
            <div class="subtitle">Outorgado com distinção acadêmica</div>

            <div class="statement">
                Certificamos para os devidos fins de direito que
            </div>
            
            <div class="student-name">
                {{ $nome_aluno }}
            </div>
            
            <div class="statement">
                concluiu com êxito o treinamento de desenvolvimento profissional no curso<br>
                <span class="course-name">"{{ $nome_curso }}"</span>
            </div>
            
            <div class="details">
                Carga Horária: <strong>{{ $carga_horaria }} horas</strong> &bull; Concluído em: <strong>{{ $data_conclusao }}</strong>
            </div>

            <table class="signatures-table">
                <tr>
                    <td class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-title">GearUp Education</div>
                        <div class="signature-subtitle">Diretoria Acadêmica</div>
                    </td>
                    <td class="signature-cell">
                        <div class="signature-line"></div>
                        <div class="signature-title">Coordenador do Curso</div>
                        <div class="signature-subtitle">Instrutor Principal</div>
                    </td>
                </tr>
            </table>

            <div class="auth-code">
                Código de Autenticidade: {{ $codigo_autenticidade }}
            </div>
        </div>
    </div>
</body>
</html>