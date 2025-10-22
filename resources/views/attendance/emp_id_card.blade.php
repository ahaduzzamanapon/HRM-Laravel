<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bank Employee ID Card - Side by Side</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --w: 340px;
    --h: 212px;
    --radius: 14px;
    --primary: linear-gradient(135deg, #0f4c81, #1b9bd7);
    --accent: #0a2540;
    --muted: rgba(255,255,255,0.85);
    --shadow: 0 8px 30px rgba(11,20,30,0.35);
  }
  body{
    background: #f3f7fc;
    font-family: "Inter", sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    gap: 40px;
    flex-wrap: wrap;
  }

  .id-card{
    width: var(--w);
    height: var(--h);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
    position: relative;
  }

  /* FRONT SIDE */
  .front{
    background: var(--primary);
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 16px;
  }
  .bank-header{
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .bank-header img{
    width: 48px;
    height: 48px;
    border-radius: 8px;
  }
  .bank-name{
    font-size: 1rem;
    font-weight: 700;
  }

  .employee-info{
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .photo{
    width: 85px;
    height: 100px;
    border-radius: 10px;
    overflow: hidden;
    border: 3px solid rgba(255,255,255,0.2);
  }
  .photo img{
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .details{
    flex: 1;
    padding-left: 16px;
  }
  .emp-name{
    font-weight: 700;
    font-size: 1.1rem;
  }
  .role{
    font-size: 0.9rem;
    opacity: 0.9;
  }
  .meta{
    margin-top: 8px;
    font-size: 0.8rem;
    opacity: 0.9;
  }
  .qr{
    width: 70px;
    height: 70px;
    background: white;
    border-radius: 8px;
    display: grid;
    place-items: center;
    margin-top: 10px;
  }
  .qr img{
    width: 80%;
  }
  .footer{
    font-size: 0.75rem;
    display: flex;
    justify-content: space-between;
    opacity: 0.9;
  }

  /* BACK SIDE */
  .back{
    background: linear-gradient(180deg,#0f385d 0%, #143f62 100%);
    color: white;
    padding: 16px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }
  .mag-strip{
    height: 36px;
    background: linear-gradient(90deg,#0b0b0b,#2a2a2a);
    border-radius: 6px;
  }
  .info-block{
    font-size: 0.8rem;
    margin-top: 10px;
  }
  .signature{
    width: 60%;
    background: #fff;
    color: #0a2540;
    border-radius: 6px;
    padding: 8px 10px;
    font-weight: 600;
    font-size: 0.8rem;
    margin-top: 10px;
  }
  .fine-print{
    font-size: 0.7rem;
    opacity: 0.85;
    line-height: 1.2;
  }
</style>
</head>
<body>

<!-- FRONT SIDE -->
<div class="id-card front">
  <div class="bank-header">
    <div style="display:flex;align-items:center;gap:10px;">
      <img src="{{ asset('salary_logo.jpg') }}" alt="Bank Logo">
      <div>
        <div class="bank-name">ACME BANK</div>
        <div style="font-size:0.8rem;opacity:0.85;">Authorized Employee</div>
      </div>
    </div>
    <div style="font-size:0.8rem;">EMP ID: <b>ACB-02347</b></div>
  </div>

  <div class="employee-info">
    <div class="photo">
      <img src="https://images.unsplash.com/photo-1607746882042-944635dfe10e?q=80&w=500" alt="Employee Photo">
    </div>
    <div class="details">
      <div class="emp-name">Md. Example Shakil</div>
      <div class="role">Relationship Manager</div>
      <div class="meta">Department: Retail Banking</div>
      <div class="meta">Branch: Dhaka Main</div>
    </div>
  </div>

  <div class="footer">
    <div>Contact: +8801X-XXXX-XXXX</div>
    <div>Valid Till: 31 Dec 2028</div>
  </div>
</div>

<!-- BACK SIDE -->
<div class="id-card back">
  <div>
    <div class="mag-strip"></div>
    <div class="info-block" style="margin-top:10px;">
      <strong>Emergency Contact:</strong><br>
      Branch Manager<br>
      +8801X-XXXX-XXXX<br>
      123 Bank Street, Dhaka
    </div>
    <div class="signature">Authorized Signature</div>
  </div>

  <div class="fine-print">
    This card remains the property of ACME Bank and must be returned upon request.
    Unauthorized use is strictly prohibited.
  </div>
</div>

</body>
</html>
