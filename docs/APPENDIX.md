# APPENDIX — MAPOLY Smart Attendance

**Institution:** Moshood Abiola Polytechnic (MAPOLY), Abeokuta  
**Project:** Smart Attendance Monitoring System Using QR Codes and Mobile Devices

## Project owners

| Name | Matric Number |
|------|---------------|
| Ojebiyi Samson Oluwaferanmi | 24/145/0196 |
| Adeniyi Gbenga Daniel | 24/145/0082 |
| Akinwale Elijah Idowu | 24/145/0100 |

## Diagram files (use these in your report)

| Appendix | Description | PNG (for report) |
|----------|-------------|------------------|
| A | Attendance marking algorithm | `public/diagrams/attendance-algorithm.png` |
| B | System flowchart (lecturer + student) | `public/diagrams/attendance-flowchart.png` |
| C | System architecture | `public/diagrams/attendance-architecture.png` |

Each diagram includes the **bold MAPOLY logo** and project owners (including **Adeniyi Gbenga Daniel**).

Also available: matching `.svg` sources and HTML appendix pages in `public/diagrams/`.

## Appendix A — Algorithm (summary)

1. Student scans QR → extract token  
2. If session not found → reject  
3. If session not active / expired / closed → reject  
4. If student not enrolled in course → reject  
5. If already marked → reject (duplicate)  
6. Create attendance record  
7. Send confirmation email immediately (no queue)  
8. Show success  

## Appendix B — Flowchart (summary)

- **Lecturer/Admin:** login → create course → assign students → create/activate session → display QR → optional manual mark → close session → reports  
- **Student:** register/login → enrolled by lecturer → scan QR → validation → record + email → success  

## Appendix C — Architecture (summary)

Browser → Routes → Middleware/Policies → Controllers → Services → Eloquent Models → MySQL  
Plus QR generation and SMTP mail (synchronous).

## Logo

Official MAPOLY crest used in the application: `public/images/mapoly-logo.png`
