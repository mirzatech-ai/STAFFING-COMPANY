<?php
/**
 * ══════════════════════════════════════════════════════════════════════════
 * HUMANLESS WORKFORCE — STAFFING AGENCY BACKEND
 * api/staff.php v4.0
 *
 * 460 roles · 58 agencies · Maya-governed · Fortune 500 ready
 * Multilingual · AI-powered matching · Self-learning
 *
 * Domain: ai-staffing.agency
 * Governor: Maya (iamsuperio.cloud)
 * Mo Osmanović · Emaaa LLC · MirzaTech.ai · BANG BANG 🇧🇦
 * PHP 7.4 ONLY — KOVAČ RULES
 * ══════════════════════════════════════════════════════════════════════════
 */

ob_start();
@error_reporting(0);
@ini_set('display_errors', '0');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

// ── MAYA NEXUS CONNECTION ──────────────────────────────────────────────────
define('MAYA_HQ',      'https://iamsuperio.cloud/api/index.php');
define('MAYA_SECRET',  'MirzaTechNexusMasterKey2024BangBang');
define('DOMAIN',       'ai-staffing.agency');
define('DOMAIN_KEY',   hash('sha256', DOMAIN . ':' . MAYA_SECRET));
define('LEARN_FILE',   '/tmp/asg_maya/.staff_learning.json');
define('LEAD_FILE',    '/tmp/asg_maya/.leads.json');
define('STAT_FILE',    '/tmp/asg_maya/.stats.json');
@mkdir('/tmp/asg_maya/', 0777, true);


// ── RULE_75_UNIQUE_AGENCY_IMAGE — swap image1 to per-agency unique ─────────
function rule75_apply_unique_images(array $agencies): array {
    $asset_dir = '/home/ai-staffing.agency/public_html/assets/agency';
    foreach ($agencies as &$a) {
        if (!isset($a['id'])) continue;
        $unique = $asset_dir . '/' . $a['id'] . '.jpg';
        if (file_exists($unique) && filesize($unique) > 10000) {
            // Promote unique image to primary, preserve original as secondary
            $prev1 = $a['image1'] ?? '';
            $a['image1'] = '/assets/agency/' . $a['id'] . '.jpg';
            if (!empty($prev1) && empty($a['image2'])) {
                $a['image2'] = $prev1;
            }
        }
    }
    return $agencies;
}

function s_out($data, $code = 200) {
    while (ob_get_level() > 0) ob_end_clean();
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// ── THE FULL 35-AGENCY ROSTER ──────────────────────────────────────────────
function get_agencies() {
    return [

        // ── 01 ENGINEERING & DEVELOPMENT ───────────────────────────────────
        [
            'id'    => 'engineering',
            'name'  => 'Engineering & Development',
            'icon'  => '⚙️',
            'color' => '#00c8ff',
            'tagline' => 'Architects of the digital frontier',
            'description' => 'Elite AI engineers who build the infrastructure of tomorrow. From cloud-native architectures to performance-critical systems, our engineering staff delivers at enterprise scale.',
            'image1'=> 'images/robots-working.jpg',
            'image2'=> 'images/ai-brain.jpg',
            'maya_prompt' => 'You are an elite engineering staffing specialist. Match technical skills, architecture patterns, and system design expertise.',
            'roles' => [
                ['title'=>'Senior Full-Stack Engineer','tier'=>'senior','daily_rate'=>850,'skills'=>['React','Node.js','PostgreSQL','AWS']],
                ['title'=>'Backend Systems Engineer','tier'=>'senior','daily_rate'=>900,'skills'=>['Go','Rust','Kubernetes','gRPC']],
                ['title'=>'Frontend Engineer','tier'=>'mid','daily_rate'=>700,'skills'=>['React','TypeScript','GraphQL','CSS']],
                ['title'=>'DevOps Engineer','tier'=>'senior','daily_rate'=>950,'skills'=>['Terraform','Kubernetes','CI/CD','AWS']],
                ['title'=>'Site Reliability Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['SRE','Observability','SLO/SLA','Incident Response']],
                ['title'=>'Mobile Developer','tier'=>'mid','daily_rate'=>750,'skills'=>['Swift','Kotlin','React Native','Flutter']],
                ['title'=>'API Architect','tier'=>'senior','daily_rate'=>1100,'skills'=>['REST','GraphQL','gRPC','API Gateway']],
                ['title'=>'Database Engineer','tier'=>'senior','daily_rate'=>900,'skills'=>['PostgreSQL','Redis','Cassandra','Query Optimization']],
                ['title'=>'Cloud Infrastructure Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['AWS','GCP','Azure','IaC']],
                ['title'=>'Security Engineer','tier'=>'senior','daily_rate'=>1050,'skills'=>['AppSec','SAST/DAST','OAuth','Zero Trust']],
                ['title'=>'Performance Engineer','tier'=>'senior','daily_rate'=>950,'skills'=>['Load Testing','Profiling','Caching','CDN']],
                ['title'=>'Systems Architect','tier'=>'executive','daily_rate'=>1500,'skills'=>['Distributed Systems','Microservices','Event-Driven','Scale']],
            ],
        ],

        // ── 02 AI RESEARCH & SCIENCE ────────────────────────────────────────
        [
            'id'    => 'ai-research',
            'name'  => 'AI Research & Science',
            'icon'  => '🧠',
            'color' => '#7c3aed',
            'tagline' => 'The minds that redefine what machines can do',
            'description' => 'World-class AI researchers and engineers who push the boundaries of machine intelligence. From foundational models to deployed production systems.',
            'image1'=> 'images/ai-brain.jpg',
            'image2'=> 'images/cyborg-hero.jpg',
            'maya_prompt' => 'You are an AI research talent specialist. Evaluate publications, model architectures, and research contributions.',
            'roles' => [
                ['title'=>'Machine Learning Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['PyTorch','TensorFlow','MLOps','Feature Engineering']],
                ['title'=>'Deep Learning Researcher','tier'=>'senior','daily_rate'=>1200,'skills'=>['Transformers','CNNs','Research Papers','CUDA']],
                ['title'=>'NLP Specialist','tier'=>'senior','daily_rate'=>1150,'skills'=>['LLMs','BERT','RAG','Text Generation']],
                ['title'=>'Computer Vision Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['YOLO','OpenCV','Object Detection','Image Segmentation']],
                ['title'=>'AI Ethics Officer','tier'=>'senior','daily_rate'=>950,'skills'=>['Responsible AI','Bias Auditing','Fairness','Governance']],
                ['title'=>'Research Scientist','tier'=>'executive','daily_rate'=>1500,'skills'=>['Publications','Novel Architectures','Benchmarking','Ablations']],
                ['title'=>'MLOps Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['MLflow','Kubeflow','Model Registry','A/B Testing']],
                ['title'=>'Generative AI Specialist','tier'=>'senior','daily_rate'=>1200,'skills'=>['Stable Diffusion','GPT','Prompt Engineering','Fine-tuning']],
                ['title'=>'Reinforcement Learning Engineer','tier'=>'senior','daily_rate'=>1250,'skills'=>['PPO','RLHF','OpenAI Gym','Policy Gradient']],
                ['title'=>'Multimodal AI Engineer','tier'=>'senior','daily_rate'=>1300,'skills'=>['Vision-Language','Audio-Visual','Cross-Modal','Embedding']],
                ['title'=>'AI Product Researcher','tier'=>'senior','daily_rate'=>1000,'skills'=>['User Research','Model Evaluation','Product-AI Fit']],
                ['title'=>'AI Safety Researcher','tier'=>'executive','daily_rate'=>1400,'skills'=>['Alignment','Red-Teaming','Interpretability','Robustness']],
            ],
        ],

        // ── 03 CYBERSECURITY ────────────────────────────────────────────────
        [
            'id'    => 'cybersecurity',
            'name'  => 'Cybersecurity',
            'icon'  => '🛡️',
            'color' => '#dc2626',
            'tagline' => 'Unbreachable defense for the digital era',
            'description' => 'Elite security professionals who protect critical infrastructure. Our cyber staff operates with military-grade precision across every threat vector.',
            'image1'=> 'images/staffing-security.jpg',
            'image2'=> 'images/cyborg-hero.jpg',
            'maya_prompt' => 'You are a cybersecurity talent specialist. Assess certifications, threat experience, and security clearances.',
            'roles' => [
                ['title'=>'Chief Information Security Officer','tier'=>'executive','daily_rate'=>2000,'skills'=>['Security Strategy','Board Communication','GRC','Risk Management']],
                ['title'=>'Penetration Tester','tier'=>'senior','daily_rate'=>1100,'skills'=>['Kali Linux','Metasploit','Bug Bounty','CVE Research']],
                ['title'=>'Security Analyst','tier'=>'mid','daily_rate'=>700,'skills'=>['SIEM','Log Analysis','Threat Hunting','Incident Triage']],
                ['title'=>'Threat Intelligence Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['OSINT','Dark Web','IOCs','TTPs']],
                ['title'=>'Incident Responder','tier'=>'senior','daily_rate'=>1200,'skills'=>['Digital Forensics','DFIR','Memory Analysis','Chain of Custody']],
                ['title'=>'Cloud Security Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['AWS Security','Azure Defender','CSPM','Zero Trust']],
                ['title'=>'Identity Access Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['IAM','PAM','SSO','Zero Trust']],
                ['title'=>'Security Architect','tier'=>'executive','daily_rate'=>1600,'skills'=>['Security Design','Zero Trust Architecture','SABSA','TOGAF']],
                ['title'=>'Compliance Officer','tier'=>'senior','daily_rate'=>850,'skills'=>['ISO 27001','SOC 2','GDPR','HIPAA']],
                ['title'=>'SOC Analyst','tier'=>'mid','daily_rate'=>650,'skills'=>['SIEM','EDR','Playbooks','Alert Triage']],
                ['title'=>'Red Team Operator','tier'=>'senior','daily_rate'=>1300,'skills'=>['Adversary Simulation','C2 Frameworks','Social Engineering','Physical Security']],
            ],
        ],

        // ── 04 DATA INTELLIGENCE ────────────────────────────────────────────
        [
            'id'    => 'data-intelligence',
            'name'  => 'Data Intelligence',
            'icon'  => '📊',
            'color' => '#059669',
            'tagline' => 'Transform raw data into competitive advantage',
            'description' => 'Data professionals who turn information chaos into strategic clarity. From data pipelines to executive dashboards, we deliver intelligence at scale.',
            'image1'=> 'images/ai-brain.jpg',
            'image2'=> 'images/staffing-dept1.jpg',
            'maya_prompt' => 'You are a data intelligence staffing specialist. Assess SQL mastery, statistical knowledge, and data storytelling skills.',
            'roles' => [
                ['title'=>'Data Scientist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Python','Statistics','Modeling','Experimentation']],
                ['title'=>'Data Engineer','tier'=>'senior','daily_rate'=>950,'skills'=>['Spark','Airflow','dbt','Data Warehousing']],
                ['title'=>'Business Intelligence Analyst','tier'=>'mid','daily_rate'=>700,'skills'=>['Tableau','Power BI','SQL','KPI Design']],
                ['title'=>'Data Architect','tier'=>'executive','daily_rate'=>1400,'skills'=>['Data Modeling','Lakehouse','Governance','MDM']],
                ['title'=>'Analytics Engineer','tier'=>'senior','daily_rate'=>900,'skills'=>['dbt','Snowflake','BigQuery','Semantic Layer']],
                ['title'=>'Data Quality Manager','tier'=>'senior','daily_rate'=>800,'skills'=>['Great Expectations','Data Contracts','Lineage','Profiling']],
                ['title'=>'Quantitative Analyst','tier'=>'senior','daily_rate'=>1200,'skills'=>['R','Python','Financial Modeling','Time Series']],
                ['title'=>'Forecasting Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['Prophet','ARIMA','Demand Planning','Scenario Modeling']],
                ['title'=>'Data Visualization Specialist','tier'=>'mid','daily_rate'=>750,'skills'=>['D3.js','Tableau','Looker','Visual Storytelling']],
                ['title'=>'BI Developer','tier'=>'mid','daily_rate'=>700,'skills'=>['Power BI','SSRS','DAX','ETL']],
                ['title'=>'Master Data Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['MDM','Data Governance','Stewardship','Deduplication']],
            ],
        ],

        // ── 05 MARKETING & GROWTH ───────────────────────────────────────────
        [
            'id'    => 'marketing-growth',
            'name'  => 'Marketing & Growth',
            'icon'  => '📈',
            'color' => '#d97706',
            'tagline' => 'Engineered growth at global scale',
            'description' => 'Growth architects who build revenue engines. Data-driven marketers with AI-powered strategies that capture Fortune 500 market share across every channel.',
            'image1'=> 'images/marketing-hero.jpg',
            'image2'=> 'images/ai-staffing.jpg',
            'maya_prompt' => 'You are a marketing talent specialist. Evaluate campaign performance metrics, channel mastery, and growth track records.',
            'roles' => [
                ['title'=>'Chief Marketing Officer','tier'=>'executive','daily_rate'=>2500,'skills'=>['Brand Strategy','P&L','Board Communication','Category Creation']],
                ['title'=>'Growth Hacker','tier'=>'senior','daily_rate'=>950,'skills'=>['Viral Loops','Experimentation','North Star Metric','Funnel Optimization']],
                ['title'=>'SEO Strategist','tier'=>'senior','daily_rate'=>800,'skills'=>['Technical SEO','Content Strategy','Link Building','Core Web Vitals']],
                ['title'=>'Content Marketing Manager','tier'=>'senior','daily_rate'=>750,'skills'=>['Content Strategy','Editorial Calendar','Thought Leadership','Distribution']],
                ['title'=>'Email Marketing Manager','tier'=>'mid','daily_rate'=>650,'skills'=>['Klaviyo','HubSpot','Segmentation','A/B Testing']],
                ['title'=>'Performance Marketing Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Google Ads','Meta Ads','Attribution','ROAS Optimization']],
                ['title'=>'Brand Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Brand Identity','Positioning','Visual Systems','Brand Voice']],
                ['title'=>'Social Media Manager','tier'=>'mid','daily_rate'=>600,'skills'=>['LinkedIn','Twitter/X','Content Creation','Community Management']],
                ['title'=>'Marketing Analyst','tier'=>'mid','daily_rate'=>700,'skills'=>['Marketing Mix Modeling','Attribution','Cohort Analysis','LTV Modeling']],
                ['title'=>'Conversion Rate Optimizer','tier'=>'senior','daily_rate'=>850,'skills'=>['Landing Pages','Heatmaps','A/B Testing','Copywriting']],
                ['title'=>'PR & Communications Manager','tier'=>'senior','daily_rate'=>800,'skills'=>['Media Relations','Crisis Comms','Thought Leadership','Press Releases']],
                ['title'=>'Influencer Marketing Manager','tier'=>'mid','daily_rate'=>650,'skills'=>['Creator Economy','Partnerships','Campaign Management','ROI Tracking']],
            ],
        ],

        // ── 06 SALES & REVENUE ──────────────────────────────────────────────
        [
            'id'    => 'sales-revenue',
            'name'  => 'Sales & Revenue',
            'icon'  => '💰',
            'color' => '#16a34a',
            'tagline' => 'Closing deals at the speed of AI',
            'description' => 'Revenue-generating machines trained on the world\'s best sales methodologies. Our AI sales staff never sleeps, never misses a follow-up, and always closes.',
            'image1'=> 'images/hero-ai-team.jpg',
            'image2'=> 'images/cyborgs2.jpg',
            'maya_prompt' => 'You are a sales talent specialist. Evaluate quota attainment, deal sizes, sales cycle mastery, and CRM proficiency.',
            'roles' => [
                ['title'=>'VP of Sales','tier'=>'executive','daily_rate'=>2200,'skills'=>['Revenue Strategy','Team Building','Forecasting','Enterprise Deals']],
                ['title'=>'Account Executive','tier'=>'senior','daily_rate'=>900,'skills'=>['MEDDIC','Challenger Sale','Contract Negotiation','Pipeline Management']],
                ['title'=>'Sales Development Representative','tier'=>'junior','daily_rate'=>400,'skills'=>['Cold Outreach','LinkedIn','Sequencing','Discovery Calls']],
                ['title'=>'Sales Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Technical Demos','POC Management','Solution Architecture','RFP Response']],
                ['title'=>'Revenue Operations Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Salesforce','Revenue Intelligence','Process Design','Analytics']],
                ['title'=>'Customer Success Manager','tier'=>'mid','daily_rate'=>750,'skills'=>['QBRs','Churn Prevention','Upsell','Health Scores']],
                ['title'=>'Enterprise Sales Representative','tier'=>'senior','daily_rate'=>1000,'skills'=>['F500 Relationships','Multi-stakeholder','Long Cycle','Strategic Accounts']],
                ['title'=>'Channel Partner Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Partner Ecosystems','Resellers','Co-Sell','MDF']],
                ['title'=>'Sales Enablement Manager','tier'=>'senior','daily_rate'=>800,'skills'=>['Training','Playbooks','Onboarding','Content Strategy']],
                ['title'=>'Business Development Representative','tier'=>'mid','daily_rate'=>600,'skills'=>['Strategic Partnerships','Alliance Management','Deal Structuring']],
                ['title'=>'Sales Analyst','tier'=>'mid','daily_rate'=>650,'skills'=>['Pipeline Analysis','Win/Loss','Compensation Design','Forecasting']],
            ],
        ],

        // ── 07 DESIGN & CREATIVE ────────────────────────────────────────────
        [
            'id'    => 'design-creative',
            'name'  => 'Design & Creative',
            'icon'  => '🎨',
            'color' => '#ec4899',
            'tagline' => 'Where art meets algorithm',
            'description' => 'Visionaries who craft the interfaces, brands, and experiences that define tomorrow\'s digital world. AI-assisted creativity at its highest form.',
            'image1'=> 'images/marketing-hero.jpg',
            'image2'=> 'images/hero2.jpg',
            'maya_prompt' => 'You are a creative talent specialist. Evaluate portfolio quality, design systems thinking, and user-centered approach.',
            'roles' => [
                ['title'=>'Creative Director','tier'=>'executive','daily_rate'=>1800,'skills'=>['Vision','Brand','Campaign Direction','Team Leadership']],
                ['title'=>'Art Director','tier'=>'senior','daily_rate'=>1100,'skills'=>['Visual Identity','Art Direction','Campaign Design','Concept Development']],
                ['title'=>'UI Designer','tier'=>'mid','daily_rate'=>750,'skills'=>['Figma','Design Systems','Component Libraries','Accessibility']],
                ['title'=>'UX Designer','tier'=>'senior','daily_rate'=>900,'skills'=>['User Research','Wireframing','Prototyping','Usability Testing']],
                ['title'=>'Motion Designer','tier'=>'senior','daily_rate'=>950,'skills'=>['After Effects','Lottie','3D Animation','Storytelling']],
                ['title'=>'Brand Designer','tier'=>'senior','daily_rate'=>950,'skills'=>['Identity Design','Logo','Typography','Brand Guidelines']],
                ['title'=>'Product Designer','tier'=>'senior','daily_rate'=>950,'skills'=>['0-to-1 Design','Feature Design','Design Thinking','Cross-Functional']],
                ['title'=>'3D Artist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Blender','Cinema 4D','Rendering','Product Visualization']],
                ['title'=>'Graphic Designer','tier'=>'mid','daily_rate'=>600,'skills'=>['Adobe Suite','Print','Digital','Layouts']],
                ['title'=>'Design Systems Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Storybook','Design Tokens','React Components','Documentation']],
                ['title'=>'Visual Designer','tier'=>'mid','daily_rate'=>700,'skills'=>['Composition','Color Theory','Illustration','Iconography']],
            ],
        ],

        // ── 08 CONTENT & MEDIA ──────────────────────────────────────────────
        [
            'id'    => 'content-media',
            'name'  => 'Content & Media',
            'icon'  => '📝',
            'color' => '#0891b2',
            'tagline' => 'Stories that move markets',
            'description' => 'Content machines that produce at scale without sacrificing quality. From technical documentation to viral video content, engineered to capture attention.',
            'image1'=> 'images/hero-ai-team.jpg',
            'image2'=> 'images/ai-staffing.jpg',
            'maya_prompt' => 'You are a content and media talent specialist. Evaluate writing quality, SEO knowledge, video production, and audience growth.',
            'roles' => [
                ['title'=>'Content Strategist','tier'=>'senior','daily_rate'=>850,'skills'=>['Content Pillars','Editorial Calendar','Distribution','Measurement']],
                ['title'=>'Technical Writer','tier'=>'mid','daily_rate'=>700,'skills'=>['API Docs','Developer Guides','Markdown','DITA']],
                ['title'=>'Video Producer','tier'=>'senior','daily_rate'=>900,'skills'=>['Pre-Production','Editing','YouTube','B-Roll Storytelling']],
                ['title'=>'Podcast Producer','tier'=>'mid','daily_rate'=>650,'skills'=>['Audio Editing','Guest Management','Distribution','Sponsorships']],
                ['title'=>'Copywriter','tier'=>'mid','daily_rate'=>650,'skills'=>['Direct Response','UX Copy','Brand Voice','Conversion Writing']],
                ['title'=>'Editorial Manager','tier'=>'senior','daily_rate'=>800,'skills'=>['Team Management','Editorial Standards','Fact-Checking','Publishing']],
                ['title'=>'Scriptwriter','tier'=>'mid','daily_rate'=>700,'skills'=>['Narrative Structure','Storyboarding','Dialogue','Adaptation']],
                ['title'=>'Social Content Creator','tier'=>'junior','daily_rate'=>400,'skills'=>['Short-Form Video','TikTok','Reels','Trend Awareness']],
                ['title'=>'Content Analyst','tier'=>'mid','daily_rate'=>600,'skills'=>['Performance Metrics','Content Scoring','Competitive Analysis','SEO']],
                ['title'=>'Multimedia Producer','tier'=>'senior','daily_rate'=>850,'skills'=>['Cross-Platform','Audio','Video','Interactive']],
                ['title'=>'Content Distribution Manager','tier'=>'mid','daily_rate'=>700,'skills'=>['Syndication','Amplification','Platform Partnerships','Email']],
            ],
        ],

        // ── 09 FINANCE & ACCOUNTING ─────────────────────────────────────────
        [
            'id'    => 'finance-accounting',
            'name'  => 'Finance & Accounting',
            'icon'  => '💹',
            'color' => '#0f766e',
            'tagline' => 'Precision financial intelligence',
            'description' => 'AI-powered financial professionals who combine quantitative mastery with strategic insight. From startup runway management to Fortune 500 treasury operations.',
            'image1'=> 'images/testimonial-bg.jpg',
            'image2'=> 'images/ai-brain.jpg',
            'maya_prompt' => 'You are a finance talent specialist. Evaluate CPA/CFA credentials, ERP proficiency, and financial modeling capabilities.',
            'roles' => [
                ['title'=>'Chief Financial Officer','tier'=>'executive','daily_rate'=>2500,'skills'=>['Capital Markets','M&A','Board Relations','Fundraising']],
                ['title'=>'Financial Analyst','tier'=>'mid','daily_rate'=>700,'skills'=>['DCF','LBO','Financial Modeling','Excel','Python']],
                ['title'=>'Controller','tier'=>'senior','daily_rate'=>1100,'skills'=>['Close Process','GAAP','Consolidation','Audit']],
                ['title'=>'Tax Specialist','tier'=>'senior','daily_rate'=>950,'skills'=>['Tax Planning','Transfer Pricing','R&D Credits','International Tax']],
                ['title'=>'FP&A Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Budgeting','Forecasting','Variance Analysis','Business Partnering']],
                ['title'=>'Treasury Manager','tier'=>'senior','daily_rate'=>1050,'skills'=>['Cash Management','FX Hedging','Liquidity','Banking Relations']],
                ['title'=>'Audit Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Internal Audit','SOX','Risk Assessment','Controls Testing']],
                ['title'=>'Risk Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['Enterprise Risk','Quantitative Risk','Scenarios','Stress Testing']],
                ['title'=>'Payroll Manager','tier'=>'mid','daily_rate'=>650,'skills'=>['Multi-Country Payroll','HR Systems','Compliance','Benefits']],
                ['title'=>'Revenue Recognition Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['ASC 606','IFRS 15','Contract Analysis','Revenue Schedules']],
                ['title'=>'Accounts Payable Manager','tier'=>'mid','daily_rate'=>600,'skills'=>['AP Automation','Vendor Management','3-Way Match','Payment Processing']],
            ],
        ],

        // ── 10 LEGAL & COMPLIANCE ───────────────────────────────────────────
        [
            'id'    => 'legal-compliance',
            'name'  => 'Legal & Compliance',
            'icon'  => '⚖️',
            'color' => '#1d4ed8',
            'tagline' => 'Bulletproof legal infrastructure',
            'description' => 'AI-enabled legal professionals who protect your business at every scale. From IP portfolios to international regulatory compliance, built for the modern enterprise.',
            'image1'=> 'images/staffing-security.jpg',
            'image2'=> 'images/cyborg-hero.jpg',
            'maya_prompt' => 'You are a legal talent specialist. Evaluate bar admissions, practice areas, and deal experience.',
            'roles' => [
                ['title'=>'General Counsel','tier'=>'executive','daily_rate'=>2200,'skills'=>['Corporate Law','Board Governance','M&A','Litigation Strategy']],
                ['title'=>'IP Attorney','tier'=>'senior','daily_rate'=>1400,'skills'=>['Patent Filing','Trade Secrets','Trademark','Licensing']],
                ['title'=>'Contract Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['CLM','Negotiation','Redlining','Commercial Terms']],
                ['title'=>'Privacy Officer','tier'=>'senior','daily_rate'=>1100,'skills'=>['GDPR','CCPA','PIPL','Privacy Engineering']],
                ['title'=>'Regulatory Affairs Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['FDA','SEC','FINRA','Regulatory Strategy']],
                ['title'=>'Employment Law Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['Labor Law','Dispute Resolution','Compliance','HR Law']],
                ['title'=>'Corporate Secretary','tier'=>'senior','daily_rate'=>850,'skills'=>['Board Minutes','Entity Management','Annual Reports','Governance']],
                ['title'=>'Compliance Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Compliance Program','Training','Investigations','Policy Drafting']],
                ['title'=>'M&A Attorney','tier'=>'executive','daily_rate'=>1800,'skills'=>['Due Diligence','Deal Structure','Integration','Cross-Border']],
                ['title'=>'Litigation Support Specialist','tier'=>'mid','daily_rate'=>700,'skills'=>['eDiscovery','Document Review','Case Management','Legal Research']],
            ],
        ],

        // ── 11 HUMAN RESOURCES ──────────────────────────────────────────────
        [
            'id'    => 'human-resources',
            'name'  => 'Human Resources',
            'icon'  => '👥',
            'color' => '#7c3aed',
            'tagline' => 'People operations powered by intelligence',
            'description' => 'Next-generation HR professionals who combine data science with human intuition. Building the talent engines that power the world\'s most innovative companies.',
            'image1'=> 'images/hero-ai-team.jpg',
            'image2'=> 'images/cyborgs1.jpg',
            'maya_prompt' => 'You are an HR talent specialist. Evaluate HRIS mastery, people analytics capability, and organizational development track record.',
            'roles' => [
                ['title'=>'Chief Human Resources Officer','tier'=>'executive','daily_rate'=>2000,'skills'=>['Org Design','Culture','Board Relations','Change Management']],
                ['title'=>'Talent Acquisition Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Executive Search','Employer Branding','ATS','Sourcing']],
                ['title'=>'People Analytics Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Workforce Analytics','Attrition Modeling','Headcount Planning','Python']],
                ['title'=>'L&D Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['LMS','Curriculum Design','Leadership Development','Skills Mapping']],
                ['title'=>'Compensation Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Compensation Benchmarking','Equity Design','Pay Equity','Total Rewards']],
                ['title'=>'HR Business Partner','tier'=>'senior','daily_rate'=>900,'skills'=>['Stakeholder Management','Performance Management','ER','Change']],
                ['title'=>'Employee Experience Manager','tier'=>'mid','daily_rate'=>750,'skills'=>['Engagement Surveys','Culture Programs','Onboarding','Wellness']],
                ['title'=>'Diversity & Inclusion Officer','tier'=>'senior','daily_rate'=>950,'skills'=>['DEI Strategy','Inclusion Programs','Metrics','Community']],
                ['title'=>'Organizational Development Specialist','tier'=>'senior','daily_rate'=>900,'skills'=>['Org Design','Team Effectiveness','Culture Change','Coaching']],
                ['title'=>'Benefits Manager','tier'=>'mid','daily_rate'=>700,'skills'=>['Benefits Design','Open Enrollment','Vendor Management','Compliance']],
                ['title'=>'Culture Manager','tier'=>'mid','daily_rate'=>700,'skills'=>['Culture Initiatives','Internal Comms','Events','Recognition']],
            ],
        ],

        // ── 12 CUSTOMER SUCCESS ─────────────────────────────────────────────
        [
            'id'    => 'customer-success',
            'name'  => 'Customer Success',
            'icon'  => '🌟',
            'color' => '#ea580c',
            'tagline' => 'Turning customers into champions',
            'description' => 'AI-powered customer success professionals who reduce churn to near-zero and drive explosive net revenue retention. The secret weapon behind the best SaaS companies.',
            'image1'=> 'images/testimonial-bg.jpg',
            'image2'=> 'images/cyborgs2.jpg',
            'maya_prompt' => 'You are a customer success talent specialist. Evaluate NRR, churn rates managed, QBR quality, and onboarding track records.',
            'roles' => [
                ['title'=>'VP Customer Success','tier'=>'executive','daily_rate'=>2000,'skills'=>['NRR Ownership','CS Strategy','Team Building','Executive Relationships']],
                ['title'=>'Customer Success Manager','tier'=>'senior','daily_rate'=>800,'skills'=>['Account Health','QBRs','Risk Management','Expansion Revenue']],
                ['title'=>'Onboarding Specialist','tier'=>'mid','daily_rate'=>600,'skills'=>['Implementation','Training','Adoption Tracking','Playbooks']],
                ['title'=>'Technical Account Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Technical Depth','Solution Design','Escalations','API Integration']],
                ['title'=>'Support Engineer','tier'=>'mid','daily_rate'=>700,'skills'=>['Tier 2/3 Support','Debugging','Documentation','SLA Management']],
                ['title'=>'Customer Advocate','tier'=>'mid','daily_rate'=>600,'skills'=>['Customer Stories','Case Studies','Reference Programs','Community']],
                ['title'=>'Renewal Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Renewal Forecasting','Negotiation','Multi-Year Deals','Risk Mitigation']],
                ['title'=>'NPS Program Manager','tier'=>'mid','daily_rate'=>650,'skills'=>['Survey Design','Closed Loop Feedback','CX Analytics','Reporting']],
                ['title'=>'Customer Education Specialist','tier'=>'mid','daily_rate'=>600,'skills'=>['LMS','Course Creation','Certification','Self-Service']],
                ['title'=>'Escalation Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Executive Escalations','Crisis Management','Resolution Process','Communication']],
            ],
        ],

        // ── 13 PRODUCT MANAGEMENT ───────────────────────────────────────────
        [
            'id'    => 'product-management',
            'name'  => 'Product Management',
            'icon'  => '🎯',
            'color' => '#9333ea',
            'tagline' => 'Vision meets execution',
            'description' => 'Product visionaries who turn market opportunities into shipped products. From 0-to-1 innovation to enterprise platform scaling, our PMs drive outcomes.',
            'image1'=> 'images/ai-brain.jpg',
            'image2'=> 'images/marketing-hero.jpg',
            'maya_prompt' => 'You are a product management talent specialist. Evaluate roadmap delivery, user research quality, and metric-driven decision making.',
            'roles' => [
                ['title'=>'Chief Product Officer','tier'=>'executive','daily_rate'=>2500,'skills'=>['Product Vision','Portfolio Strategy','P&L','Innovation']],
                ['title'=>'Senior Product Manager','tier'=>'senior','daily_rate'=>1200,'skills'=>['Roadmap','PRD Writing','Stakeholder Management','A/B Testing']],
                ['title'=>'Product Analyst','tier'=>'mid','daily_rate'=>750,'skills'=>['SQL','Funnel Analysis','Feature Instrumentation','Cohort Analysis']],
                ['title'=>'Product Operations Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Tooling','Processes','Data Quality','Cross-Functional Ops']],
                ['title'=>'Growth PM','tier'=>'senior','daily_rate'=>1100,'skills'=>['Acquisition','Activation','Retention','North Star Metric']],
                ['title'=>'Technical PM','tier'=>'senior','daily_rate'=>1200,'skills'=>['API Products','Platform','Technical Specs','Engineering Partnership']],
                ['title'=>'AI Product Manager','tier'=>'senior','daily_rate'=>1300,'skills'=>['LLM Products','Model Evaluation','AI UX','Responsible AI']],
                ['title'=>'B2B Product Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Enterprise Features','Integrations','Compliance','Admin Tools']],
                ['title'=>'Consumer PM','tier'=>'senior','daily_rate'=>1000,'skills'=>['Consumer Psychology','Viral Growth','Mobile','Engagement']],
                ['title'=>'Platform PM','tier'=>'senior','daily_rate'=>1150,'skills'=>['APIs','Developer Experience','Marketplace','Ecosystem']],
                ['title'=>'Product Strategy Manager','tier'=>'executive','daily_rate'=>1500,'skills'=>['Market Analysis','Competitive Intelligence','Strategic Planning','OKRs']],
            ],
        ],

        // ── 14 OPERATIONS & LOGISTICS ───────────────────────────────────────
        [
            'id'    => 'operations-logistics',
            'name'  => 'Operations & Logistics',
            'icon'  => '⚡',
            'color' => '#ca8a04',
            'tagline' => 'Operational excellence at machine speed',
            'description' => 'Operations professionals who eliminate inefficiency and build systems that scale. AI-optimized logistics, procurement, and process management.',
            'image1'=> 'images/robots-working.jpg',
            'image2'=> 'images/cyborgs1.jpg',
            'maya_prompt' => 'You are an operations talent specialist. Evaluate process improvement metrics, logistics network expertise, and cost reduction track records.',
            'roles' => [
                ['title'=>'Chief Operating Officer','tier'=>'executive','daily_rate'=>2500,'skills'=>['Operational Strategy','Scale','P&L','Cross-Functional Leadership']],
                ['title'=>'Operations Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Process Design','KPI Management','Team Leadership','Continuous Improvement']],
                ['title'=>'Process Improvement Specialist','tier'=>'senior','daily_rate'=>850,'skills'=>['Six Sigma','Lean','Value Stream Mapping','Root Cause Analysis']],
                ['title'=>'Project Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['PMP','Agile','Stakeholder Management','Risk Management']],
                ['title'=>'Program Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Portfolio Management','Dependencies','OKRs','Strategic Programs']],
                ['title'=>'Vendor Manager','tier'=>'senior','daily_rate'=>800,'skills'=>['Supplier Relations','SLA Management','Negotiation','Cost Optimization']],
                ['title'=>'Procurement Specialist','tier'=>'mid','daily_rate'=>700,'skills'=>['RFP/RFI','Category Management','Contract Negotiation','Sourcing']],
                ['title'=>'Quality Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Quality Systems','ISO','Audit','Corrective Action']],
                ['title'=>'Change Management Lead','tier'=>'senior','daily_rate'=>1000,'skills'=>['PROSCI','Stakeholder Engagement','Communications','Training']],
                ['title'=>'Facilities Manager','tier'=>'mid','daily_rate'=>650,'skills'=>['Space Planning','Vendor Management','Health & Safety','Asset Management']],
            ],
        ],

        // ── 15 HEALTHCARE & MEDICAL AI ──────────────────────────────────────
        [
            'id'    => 'healthcare-medical',
            'name'  => 'Healthcare & Medical AI',
            'icon'  => '🏥',
            'color' => '#0369a1',
            'tagline' => 'AI-powered healthcare transformation',
            'description' => 'Medical AI professionals bridging clinical expertise with cutting-edge technology. Certified, compliant, and cleared to operate in the world\'s most regulated environments.',
            'image1'=> 'images/ai-brain.jpg',
            'image2'=> 'images/cyborg-hero.jpg',
            'maya_prompt' => 'You are a healthcare AI talent specialist. Verify medical credentials, HIPAA compliance knowledge, and clinical AI experience.',
            'roles' => [
                ['title'=>'Chief Medical Officer','tier'=>'executive','daily_rate'=>3000,'skills'=>['Clinical Oversight','FDA','Medical Strategy','Quality']],
                ['title'=>'Clinical AI Researcher','tier'=>'senior','daily_rate'=>1300,'skills'=>['EHR Data','Clinical Trials','FDA 510k','Medical Imaging AI']],
                ['title'=>'Health Informatics Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['HL7','FHIR','EHR Integration','Interoperability']],
                ['title'=>'Medical Data Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['Clinical Data','ICD Codes','Outcomes Analysis','Real-World Evidence']],
                ['title'=>'Telemedicine Coordinator','tier'=>'mid','daily_rate'=>700,'skills'=>['Telehealth Platforms','Patient Coordination','Clinical Workflows']],
                ['title'=>'Clinical Trial Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['GCP','Protocol Design','Site Management','Regulatory Submissions']],
                ['title'=>'HIPAA Compliance Officer','tier'=>'senior','daily_rate'=>950,'skills'=>['HIPAA/HITECH','Privacy Impact','Breach Response','Training']],
                ['title'=>'Healthcare Product Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Digital Health','FDA Class II','Patient Experience','Clinical Workflows']],
                ['title'=>'Bioinformatics Engineer','tier'=>'senior','daily_rate'=>1200,'skills'=>['Genomics','Python/R','NGS Pipeline','Drug Discovery']],
                ['title'=>'Digital Health Strategist','tier'=>'executive','daily_rate'=>1500,'skills'=>['Digital Therapeutics','RPM','Health Equity','Market Access']],
                ['title'=>'Pharmacovigilance Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Adverse Events','Signal Detection','Regulatory Reporting','Oracle Argus']],
            ],
        ],

        // ── 16 FINANCIAL TECHNOLOGY ─────────────────────────────────────────
        [
            'id'    => 'fintech',
            'name'  => 'Financial Technology',
            'icon'  => '🏦',
            'color' => '#15803d',
            'tagline' => 'The future of finance is autonomous',
            'description' => 'FinTech specialists combining financial expertise with engineering excellence. Building the payment rails, fraud detection systems, and trading algorithms that move trillions.',
            'image1'=> 'images/testimonial-bg.jpg',
            'image2'=> 'images/staffing-dept1.jpg',
            'maya_prompt' => 'You are a FinTech talent specialist. Evaluate regulatory knowledge, financial engineering, and payment systems expertise.',
            'roles' => [
                ['title'=>'FinTech Product Manager','tier'=>'senior','daily_rate'=>1200,'skills'=>['Payments','Banking APIs','Regulatory','Open Finance']],
                ['title'=>'Payment Solutions Architect','tier'=>'senior','daily_rate'=>1300,'skills'=>['PCI-DSS','ISO 20022','Real-Time Payments','Card Networks']],
                ['title'=>'Fraud Detection Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['ML Models','Rule Engines','Transaction Monitoring','AML']],
                ['title'=>'Algorithmic Trading Engineer','tier'=>'senior','daily_rate'=>1500,'skills'=>['C++/Python','HFT','Market Microstructure','Backtesting']],
                ['title'=>'Risk Modeling Analyst','tier'=>'senior','daily_rate'=>1100,'skills'=>['Credit Risk','Market Risk','Basel III','Stress Testing']],
                ['title'=>'Open Banking Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['PSD2','API Design','TPP Onboarding','Consent Management']],
                ['title'=>'Crypto/Blockchain Analyst','tier'=>'senior','daily_rate'=>1100,'skills'=>['DeFi','Smart Contracts','On-Chain Analysis','Tokenomics']],
                ['title'=>'RegTech Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Automated Compliance','KYC/AML','Reporting Automation','MiFID II']],
                ['title'=>'Financial Data Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Real-Time Data','Market Data','FIX Protocol','Data Quality']],
                ['title'=>'Credit Risk Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['Credit Scoring','IFRS 9','ECL Models','Portfolio Risk']],
                ['title'=>'Digital Wallet Product Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Mobile Payments','CBDC','Consumer Finance','Loyalty']],
            ],
        ],

        // ── 17 ROBOTICS & AUTOMATION ────────────────────────────────────────
        [
            'id'    => 'robotics-automation',
            'name'  => 'Robotics & Automation',
            'icon'  => '🤖',
            'color' => '#6366f1',
            'tagline' => 'Physical intelligence for the real world',
            'description' => 'Robotics engineers and automation architects who build the physical AI systems transforming manufacturing, logistics, healthcare, and beyond.',
            'image1'=> 'images/cyborgs1.jpg',
            'image2'=> 'images/robots-working.jpg',
            'maya_prompt' => 'You are a robotics talent specialist. Evaluate ROS expertise, control systems knowledge, and real-world deployment experience.',
            'roles' => [
                ['title'=>'Robotics Systems Engineer','tier'=>'senior','daily_rate'=>1200,'skills'=>['ROS2','C++','Kinematics','Real-Time Control']],
                ['title'=>'ROS Developer','tier'=>'senior','daily_rate'=>1100,'skills'=>['ROS/ROS2','SLAM','Navigation','Sensor Integration']],
                ['title'=>'Collaborative Robot Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['Cobots','Safety Standards','HRC','Programming']],
                ['title'=>'RPA Developer','tier'=>'mid','daily_rate'=>750,'skills'=>['UiPath','Automation Anywhere','Blue Prism','Process Mining']],
                ['title'=>'Computer Vision for Robotics','tier'=>'senior','daily_rate'=>1200,'skills'=>['3D Perception','Point Clouds','Grasping','Scene Understanding']],
                ['title'=>'Robot Safety Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['ISO 10218','Risk Assessment','Safety PLCs','CE Marking']],
                ['title'=>'Automation Product Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Industrial Automation','ROI Analysis','Integration','Market Development']],
                ['title'=>'Human-Robot Interaction Designer','tier'=>'senior','daily_rate'=>1000,'skills'=>['HRI','User Studies','Safety UX','Collaborative Workflows']],
                ['title'=>'Industrial Robot Programmer','tier'=>'senior','daily_rate'=>950,'skills'=>['KUKA','ABB','FANUC','Offline Programming']],
                ['title'=>'Autonomous Systems Architect','tier'=>'executive','daily_rate'=>1600,'skills'=>['System Architecture','Sensor Fusion','Decision Making','Safety-Critical']],
            ],
        ],

        // ── 18 BLOCKCHAIN & WEB3 ────────────────────────────────────────────
        [
            'id'    => 'blockchain-web3',
            'name'  => 'Blockchain & Web3',
            'icon'  => '⛓️',
            'color' => '#f59e0b',
            'tagline' => 'Building the decentralized future',
            'description' => 'Web3 engineers and strategists who build the protocols, DAOs, and decentralized applications reshaping finance, governance, and digital ownership.',
            'image1'=> 'images/cyborg-hero.jpg',
            'image2'=> 'images/ai-brain.jpg',
            'maya_prompt' => 'You are a Web3 talent specialist. Evaluate smart contract audit history, protocol design, and on-chain track record.',
            'roles' => [
                ['title'=>'Smart Contract Developer','tier'=>'senior','daily_rate'=>1400,'skills'=>['Solidity','Rust','Auditing','Gas Optimization']],
                ['title'=>'DeFi Protocol Engineer','tier'=>'senior','daily_rate'=>1500,'skills'=>['AMMs','Lending Protocols','Yield','MEV']],
                ['title'=>'Web3 Product Manager','tier'=>'senior','daily_rate'=>1200,'skills'=>['Token Economics','Community','DAO','User Onboarding']],
                ['title'=>'Blockchain Security Auditor','tier'=>'senior','daily_rate'=>1800,'skills'=>['Formal Verification','Fuzz Testing','Common Vulnerabilities','Reports']],
                ['title'=>'NFT Strategy Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Digital Ownership','Royalties','Marketplaces','Creator Economy']],
                ['title'=>'DAO Governance Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['Governance Design','Voting Systems','Proposal Writing','Community']],
                ['title'=>'Tokenomics Designer','tier'=>'senior','daily_rate'=>1300,'skills'=>['Economic Design','Incentive Modeling','Vesting','Distribution']],
                ['title'=>'Cross-Chain Bridge Engineer','tier'=>'senior','daily_rate'=>1500,'skills'=>['Interoperability','IBC','LayerZero','Security']],
                ['title'=>'Web3 UX Designer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Wallet UX','Onboarding','Trust','dApp Design']],
                ['title'=>'Crypto Compliance Officer','tier'=>'senior','daily_rate'=>1200,'skills'=>['FATF','AML/KYC','Exchange Licensing','VASP']],
            ],
        ],

        // ── 19 QUANTUM COMPUTING ────────────────────────────────────────────
        [
            'id'    => 'quantum-computing',
            'name'  => 'Quantum Computing',
            'icon'  => '⚛️',
            'color' => '#a21caf',
            'tagline' => 'Computing at the edge of physics',
            'description' => 'The world\'s most rare technical talent. Quantum scientists and engineers building the systems that will define computation for the next century.',
            'image1'=> 'images/ai-brain.jpg',
            'image2'=> 'images/cyborg-hero.jpg',
            'maya_prompt' => 'You are a quantum computing talent specialist. This is ultra-specialized. Verify PhD credentials, qubit experience, and published research.',
            'roles' => [
                ['title'=>'Quantum Algorithm Developer','tier'=>'executive','daily_rate'=>2000,'skills'=>['Qiskit','Cirq','VQE','QAOA']],
                ['title'=>'Quantum Hardware Engineer','tier'=>'executive','daily_rate'=>2500,'skills'=>['Qubit Control','Cryogenics','Calibration','Fabrication']],
                ['title'=>'Quantum Error Correction Specialist','tier'=>'executive','daily_rate'=>2200,'skills'=>['Surface Codes','Logical Qubits','Fault Tolerance','Threshold Theorems']],
                ['title'=>'Quantum Cryptography Engineer','tier'=>'senior','daily_rate'=>1800,'skills'=>['QKD','Post-Quantum Crypto','NIST PQC','Lattice Cryptography']],
                ['title'=>'Quantum ML Researcher','tier'=>'senior','daily_rate'=>1800,'skills'=>['QML','Quantum Kernels','Hybrid Models','Speedup Analysis']],
                ['title'=>'Quantum Software Architect','tier'=>'senior','daily_rate'=>1600,'skills'=>['Quantum Languages','Compiler Design','Optimization','SDK Development']],
                ['title'=>'Quantum Networking Specialist','tier'=>'senior','daily_rate'=>1700,'skills'=>['Quantum Repeaters','Entanglement Distribution','Quantum Internet','Protocols']],
                ['title'=>'Quantum Finance Analyst','tier'=>'senior','daily_rate'=>1500,'skills'=>['Portfolio Optimization','Monte Carlo','Risk Quantification','QUBO Formulation']],
                ['title'=>'Quantum Chemistry Researcher','tier'=>'executive','daily_rate'=>2000,'skills'=>['VQE','Drug Discovery','Materials Science','Molecular Simulation']],
                ['title'=>'Quantum Cloud Engineer','tier'=>'senior','daily_rate'=>1400,'skills'=>['AWS Braket','Azure Quantum','IBM Quantum','QPU Access']],
            ],
        ],

        // ── 20 SPACE & AEROSPACE ────────────────────────────────────────────
        [
            'id'    => 'space-aerospace',
            'name'  => 'Space & Aerospace',
            'icon'  => '🚀',
            'color' => '#1e40af',
            'tagline' => 'Engineering beyond the atmosphere',
            'description' => 'The brightest minds in aerospace bringing AI to orbit. Satellite systems, mission planning, and launch operations for the new era of commercial space.',
            'image1'=> 'images/hero2.jpg',
            'image2'=> 'images/ai-brain.jpg',
            'maya_prompt' => 'You are a space and aerospace talent specialist. Verify security clearances, systems engineering credentials, and mission experience.',
            'roles' => [
                ['title'=>'Space Systems Engineer','tier'=>'senior','daily_rate'=>1400,'skills'=>['Systems Engineering','MBSE','Subsystem Integration','V&V']],
                ['title'=>'Satellite Data Analyst','tier'=>'senior','daily_rate'=>1100,'skills'=>['Remote Sensing','Earth Observation','GIS','Image Processing']],
                ['title'=>'Mission Planning AI Specialist','tier'=>'senior','daily_rate'=>1300,'skills'=>['Trajectory Optimization','Orbital Mechanics','Mission Design','GMAT']],
                ['title'=>'Aerospace Manufacturing Engineer','tier'=>'senior','daily_rate'=>1200,'skills'=>['AS9100','Composites','DFM/DFA','Quality Systems']],
                ['title'=>'Space Weather Analyst','tier'=>'senior','daily_rate'=>1100,'skills'=>['Space Environment','Radiation Effects','Forecasting','Risk Assessment']],
                ['title'=>'Orbital Mechanics Engineer','tier'=>'senior','daily_rate'=>1300,'skills'=>['STK','GMAT','Maneuver Planning','Conjunction Analysis']],
                ['title'=>'Space Communications Specialist','tier'=>'senior','daily_rate'=>1200,'skills'=>['RF Engineering','Link Budget','Ground Stations','Protocols']],
                ['title'=>'Launch Operations Manager','tier'=>'executive','daily_rate'=>1800,'skills'=>['Launch Campaign','Range Safety','Countdown','Anomaly Resolution']],
                ['title'=>'Planetary Science AI Researcher','tier'=>'senior','daily_rate'=>1300,'skills'=>['Planetary Data','ML for Science','NASA PDS','Exoplanet Analysis']],
                ['title'=>'Space Tourism Experience Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Passenger Experience','Training Programs','Safety Comms','Brand']],
            ],
        ],

        // ── 21 ENERGY & SUSTAINABILITY ──────────────────────────────────────
        [
            'id'    => 'energy-sustainability',
            'name'  => 'Energy & Sustainability',
            'icon'  => '🌱',
            'color' => '#16a34a',
            'tagline' => 'Powering the future responsibly',
            'description' => 'Clean energy professionals and sustainability experts building the systems that will power civilization through the energy transition.',
            'image1'=> 'images/testimonial-bg.jpg',
            'image2'=> 'images/robots-working.jpg',
            'maya_prompt' => 'You are an energy and sustainability talent specialist. Evaluate regulatory knowledge, grid systems expertise, and ESG framework mastery.',
            'roles' => [
                ['title'=>'Clean Energy AI Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['Grid Modeling','Energy Markets','ML for Energy','Forecasting']],
                ['title'=>'Grid Optimization Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['SCADA','EMS','Smart Grid','Load Balancing']],
                ['title'=>'ESG Reporting Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['GRI','TCFD','CDP','Scope 1/2/3']],
                ['title'=>'Carbon Footprint Analyst','tier'=>'senior','daily_rate'=>850,'skills'=>['Life Cycle Assessment','Carbon Accounting','Offsets','Science-Based Targets']],
                ['title'=>'Renewable Energy Project Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Solar/Wind','EPC','Permitting','Grid Interconnection']],
                ['title'=>'Smart Meter Data Analyst','tier'=>'mid','daily_rate'=>750,'skills'=>['AMI Data','Demand Response','Analytics','Privacy']],
                ['title'=>'Energy Trading Analyst','tier'=>'senior','daily_rate'=>1100,'skills'=>['Power Markets','Energy Derivatives','ETRM','Risk Management']],
                ['title'=>'Sustainability Strategist','tier'=>'senior','daily_rate'=>950,'skills'=>['Net-Zero Strategy','Stakeholder Engagement','Circularity','Impact Measurement']],
                ['title'=>'EV Infrastructure Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['EVSE','V2G','Fleet Electrification','Charging Network']],
                ['title'=>'Climate Tech Product Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['CleanTech','Carbon Markets','Climate Risk','Product Development']],
            ],
        ],

        // ── 22 SUPPLY CHAIN & PROCUREMENT ───────────────────────────────────
        [
            'id'    => 'supply-chain',
            'name'  => 'Supply Chain & Procurement',
            'icon'  => '🔗',
            'color' => '#0f766e',
            'tagline' => 'Global supply chains, intelligently optimized',
            'description' => 'Supply chain engineers and procurement experts who use AI to build resilient, efficient, and transparent global supply networks.',
            'image1'=> 'images/robots-working.jpg',
            'image2'=> 'images/cyborgs2.jpg',
            'maya_prompt' => 'You are a supply chain talent specialist. Evaluate ERP expertise, forecasting track records, and supplier network size.',
            'roles' => [
                ['title'=>'Supply Chain AI Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Demand Sensing','Supply Planning','AI Forecasting','Scenario Planning']],
                ['title'=>'Procurement Analytics Specialist','tier'=>'senior','daily_rate'=>900,'skills'=>['Spend Analytics','Sourcing Strategy','TCO','Category Intelligence']],
                ['title'=>'Demand Forecasting Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Statistical Forecasting','ML Forecasting','S&OP','Inventory Optimization']],
                ['title'=>'Vendor Risk Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Supplier Assessments','Risk Scoring','Concentration Risk','Resilience']],
                ['title'=>'Logistics Optimization Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Route Optimization','Network Design','Last-Mile','Cost Modeling']],
                ['title'=>'Trade Compliance Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Export Controls','Customs','Sanctions','ITAR/EAR']],
                ['title'=>'Supplier Relationship Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['SRM','Supplier Development','Strategic Alliances','Negotiation']],
                ['title'=>'Inventory Intelligence Specialist','tier'=>'senior','daily_rate'=>900,'skills'=>['SKU Rationalization','Safety Stock','ABC Analysis','MRP']],
                ['title'=>'Warehouse Automation Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['WMS','Robotics','Pick-to-Light','Slotting Optimization']],
                ['title'=>'Transportation Analytics Lead','tier'=>'senior','daily_rate'=>950,'skills'=>['TMS','Carrier Analytics','Mode Optimization','Freight Audit']],
            ],
        ],

        // ── 23-35 ADDITIONAL AGENCIES ───────────────────────────────────────
        [
            'id'    => 'real-estate-proptech',
            'name'  => 'Real Estate & PropTech',
            'icon'  => '🏢',
            'color' => '#b45309',
            'tagline' => 'Intelligent real estate for intelligent capital',
            'description' => 'PropTech innovators combining AI with real estate expertise. Valuation models, smart building systems, and market intelligence for the world\'s largest asset class.',
            'image1'=> 'images/testimonial-bg.jpg',
            'image2'=> 'images/staffing-dept1.jpg',
            'maya_prompt' => 'You are a PropTech talent specialist. Evaluate real estate credentials, data science skills, and technology platform experience.',
            'roles' => [
                ['title'=>'PropTech Product Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['AVM','Smart Buildings','IoT Integration','User Research']],
                ['title'=>'Real Estate Data Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['Comps Analysis','Market Modeling','GIS','Valuations']],
                ['title'=>'Property Valuation AI Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['AVM Models','Feature Engineering','CAMA','Appraisal']],
                ['title'=>'Smart Building Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['BAS','IoT','HVAC Automation','Digital Twin']],
                ['title'=>'Lease Management Specialist','tier'=>'mid','daily_rate'=>700,'skills'=>['IWMS','Lease Accounting','IFRS 16','Lease Abstraction']],
                ['title'=>'PropTech Integration Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['API Integration','MRI','Yardi','Custom Development']],
                ['title'=>'Commercial RE Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['DCF','Cap Rate Modeling','Debt Underwriting','ARGUS']],
                ['title'=>'Construction Project Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Procore','BIM','Cost Management','Schedule']],
                ['title'=>'Facilities AI Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Predictive Maintenance','Energy Management','Space Utilization']],
                ['title'=>'Real Estate Marketing Manager','tier'=>'mid','daily_rate'=>700,'skills'=>['Listings','Virtual Tours','Lead Generation','CRM']],
            ],
        ],

        [
            'id'    => 'media-entertainment',
            'name'  => 'Media & Entertainment',
            'icon'  => '🎬',
            'color' => '#dc2626',
            'tagline' => 'AI-powered stories at global scale',
            'description' => 'Media and entertainment specialists who harness AI for content creation, distribution, and monetization. From streaming platforms to immersive entertainment.',
            'image1'=> 'images/marketing-hero.jpg',
            'image2'=> 'images/hero2.jpg',
            'maya_prompt' => 'You are a media and entertainment talent specialist. Evaluate content performance metrics, platform expertise, and production credits.',
            'roles' => [
                ['title'=>'AI Video Director','tier'=>'senior','daily_rate'=>1200,'skills'=>['Sora','Runway','Pika','AI-Assisted Production']],
                ['title'=>'Streaming Platform Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['OTT','Video Infrastructure','CDN','Analytics']],
                ['title'=>'Gaming AI Specialist','tier'=>'senior','daily_rate'=>1200,'skills'=>['NPC AI','Procedural Generation','Game Balance','Unity/Unreal']],
                ['title'=>'AR/VR Content Creator','tier'=>'senior','daily_rate'=>1100,'skills'=>['Unity','WebXR','Spatial Audio','3D Asset Pipeline']],
                ['title'=>'Music AI Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Generative Music','Audio ML','Synthesis','Rights Management']],
                ['title'=>'Media Rights Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Licensing','Distribution Deals','Rights Clearance','Royalty Management']],
                ['title'=>'Audience Analytics Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Recommendation Systems','Engagement Metrics','A/B Testing','Retention']],
                ['title'=>'Live Events Technology Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Event Tech','AR Overlays','Real-Time Production','Ticketing']],
                ['title'=>'Content Creator Strategist','tier'=>'mid','daily_rate'=>750,'skills'=>['Creator Economy','Platform Strategy','Monetization','Community']],
                ['title'=>'Media Distribution Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Multi-Platform','Syndication','Global Distribution','Rights']],
            ],
        ],

        [
            'id'    => 'manufacturing-industry',
            'name'  => 'Manufacturing & Industry 4.0',
            'icon'  => '🏭',
            'color' => '#78716c',
            'tagline' => 'Industrial intelligence at the factory floor',
            'description' => 'Industry 4.0 engineers bringing AI to the factory floor. Predictive maintenance, quality control, and production optimization for the modern industrial enterprise.',
            'image1'=> 'images/robots-working.jpg',
            'image2'=> 'images/cyborgs1.jpg',
            'maya_prompt' => 'You are a manufacturing and Industry 4.0 talent specialist. Evaluate PLC/SCADA experience, lean manufacturing credentials, and IoT deployment track record.',
            'roles' => [
                ['title'=>'Industrial AI Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Computer Vision for QC','Predictive Maintenance','OPC-UA','Edge AI']],
                ['title'=>'Manufacturing Operations Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['OEE','Production Planning','Lean','Six Sigma']],
                ['title'=>'Predictive Maintenance Engineer','tier'=>'senior','daily_rate'=>1050,'skills'=>['Vibration Analysis','IIoT Sensors','ML Models','CMMS']],
                ['title'=>'Quality Control AI Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Machine Vision','SPC','Defect Classification','ISO 9001']],
                ['title'=>'Industry 4.0 Architect','tier'=>'executive','daily_rate'=>1500,'skills'=>['Digital Factory','MES','ISA-95','Smart Manufacturing']],
                ['title'=>'Robot Programming Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['KUKA','ABB','Offline Programming','PLC Integration']],
                ['title'=>'Factory Automation Engineer','tier'=>'senior','daily_rate'=>1050,'skills'=>['PLC','HMI','SCADA','Siemens/Allen-Bradley']],
                ['title'=>'Production Planning Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Demand Planning','MRP/ERP','Scheduling','Capacity Planning']],
                ['title'=>'Lean Manufacturing Specialist','tier'=>'senior','daily_rate'=>900,'skills'=>['Value Stream Mapping','5S','Kaizen','Kanban']],
                ['title'=>'Digital Twin Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Simulation','Asset Models','Real-Time Sync','Predictive Analytics']],
            ],
        ],

        [
            'id'    => 'retail-ecommerce',
            'name'  => 'Retail & E-Commerce',
            'icon'  => '🛒',
            'color' => '#0891b2',
            'tagline' => 'Commerce reimagined for the AI age',
            'description' => 'Retail and e-commerce specialists who use AI to optimize every touchpoint from discovery to delivery. Building the commerce engines of tomorrow.',
            'image1'=> 'images/hero-ai-team.jpg',
            'image2'=> 'images/marketing-hero.jpg',
            'maya_prompt' => 'You are a retail and e-commerce talent specialist. Evaluate GMV managed, conversion rate improvements, and personalization system experience.',
            'roles' => [
                ['title'=>'E-Commerce Director','tier'=>'executive','daily_rate'=>1800,'skills'=>['P&L','GMV Growth','Multi-Channel','Marketplace Strategy']],
                ['title'=>'Personalization Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Recommendation Systems','A/B Testing','Segmentation','ML']],
                ['title'=>'Retail Analytics Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Customer Analytics','Basket Analysis','Store Performance','Forecasting']],
                ['title'=>'Inventory Optimization Specialist','tier'=>'senior','daily_rate'=>950,'skills'=>['Demand Forecasting','Safety Stock','SKU Rationalization','OOS Prevention']],
                ['title'=>'Pricing Strategy Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Dynamic Pricing','Competitive Intelligence','Elasticity','Margin Management']],
                ['title'=>'Omnichannel Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['BOPIS','Ship-from-Store','Unified Commerce','Channel Attribution']],
                ['title'=>'Customer Experience Manager','tier'=>'mid','daily_rate'=>750,'skills'=>['CX Design','NPS','Journey Mapping','VOC']],
                ['title'=>'Marketplace Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Amazon','Shopify','Platform Management','Listing Optimization']],
                ['title'=>'Loyalty Program Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Points Economics','Tier Design','Partner Networks','Gamification']],
                ['title'=>'Returns Optimization Manager','tier'=>'mid','daily_rate'=>700,'skills'=>['Reverse Logistics','Returns Analytics','Policy Design','Cost Reduction']],
                ['title'=>'Visual Merchandising AI Specialist','tier'=>'mid','daily_rate'=>750,'skills'=>['AI Styling','Product Photography','Virtual Try-On','Catalog']],
            ],
        ],

        [
            'id'    => 'education-training',
            'name'  => 'Education & Training',
            'icon'  => '🎓',
            'color' => '#0284c7',
            'tagline' => 'Learning systems that scale infinitely',
            'description' => 'EdTech innovators building personalized learning systems that adapt to every student. AI-powered education at the scale of nations.',
            'image1'=> 'images/ai-brain.jpg',
            'image2'=> 'images/testimonial-bg.jpg',
            'maya_prompt' => 'You are an education technology talent specialist. Evaluate instructional design credentials, LMS expertise, and learning outcome improvements.',
            'roles' => [
                ['title'=>'Chief Learning Officer','tier'=>'executive','daily_rate'=>1800,'skills'=>['L&D Strategy','Learning Ecosystem','Board Relations','ROI of Learning']],
                ['title'=>'Instructional Designer','tier'=>'senior','daily_rate'=>800,'skills'=>['ADDIE','Action Mapping','Bloom\'s Taxonomy','Articulate Storyline']],
                ['title'=>'E-Learning Developer','tier'=>'mid','daily_rate'=>700,'skills'=>['Articulate 360','HTML5','xAPI','Responsive Design']],
                ['title'=>'Adaptive Learning Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['AI Tutoring','Learning Paths','Mastery-Based','ITS Design']],
                ['title'=>'LMS Administrator','tier'=>'mid','daily_rate'=>600,'skills'=>['Moodle','Canvas','Workday Learning','SCORM/xAPI']],
                ['title'=>'Skills Assessment Manager','tier'=>'senior','daily_rate'=>850,'skills'=>['Competency Frameworks','Assessment Design','Psychometrics','Scoring']],
                ['title'=>'EdTech Product Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Learning Science','Student Analytics','Engagement','Outcome Metrics']],
                ['title'=>'Corporate Trainer','tier'=>'mid','daily_rate'=>650,'skills'=>['Facilitation','Workshop Design','Virtual Training','Coaching']],
                ['title'=>'Curriculum Developer','tier'=>'senior','daily_rate'=>800,'skills'=>['Curriculum Mapping','Standards Alignment','Interdisciplinary','Assessment']],
                ['title'=>'Academic Content Manager','tier'=>'mid','daily_rate'=>650,'skills'=>['Subject Matter Expertise','QA','Localization','Editorial']],
            ],
        ],

        [
            'id'    => 'government-public-sector',
            'name'  => 'Government & Public Sector',
            'icon'  => '🏛️',
            'color' => '#4338ca',
            'tagline' => 'AI in service of the public good',
            'description' => 'GovTech specialists with clearances, public sector expertise, and AI capability. Building the digital government systems that serve billions of citizens.',
            'image1'=> 'images/staffing-security.jpg',
            'image2'=> 'images/cyborg-hero.jpg',
            'maya_prompt' => 'You are a government technology talent specialist. Verify security clearances, compliance credentials, and public sector project history.',
            'roles' => [
                ['title'=>'Government AI Advisor','tier'=>'executive','daily_rate'=>2000,'skills'=>['Policy Design','Responsible AI','Procurement','Stakeholder Management']],
                ['title'=>'Smart City Architect','tier'=>'senior','daily_rate'=>1200,'skills'=>['IoT','Open Data','Urban Analytics','Citizen Services']],
                ['title'=>'GovTech Product Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Government Procurement','Agile in Gov','Accessibility','Compliance']],
                ['title'=>'Civic Data Scientist','tier'=>'senior','daily_rate'=>950,'skills'=>['Open Data','Public Health Analytics','Transport Data','Demographics']],
                ['title'=>'Public Policy Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['Policy Research','Regulatory Analysis','Consultation','Impact Assessment']],
                ['title'=>'Digital Government Strategist','tier'=>'senior','daily_rate'=>1100,'skills'=>['Transformation Programs','Service Design','GaaP','Digital Identity']],
                ['title'=>'Government Cybersecurity Specialist','tier'=>'senior','daily_rate'=>1200,'skills'=>['FISMA','NIST 800-53','FedRAMP','Clearance']],
                ['title'=>'Emergency Response AI Coordinator','tier'=>'senior','daily_rate'=>1000,'skills'=>['Crisis Management','Predictive Response','Comms Systems','FEMA']],
                ['title'=>'Public Finance Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['Budget Analysis','Grant Management','GAAP for Gov','CAFR']],
                ['title'=>'AI Ethics in Government Officer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Algorithmic Accountability','Bias Auditing','Public Trust','Policy']],
            ],
        ],

        [
            'id'    => 'pharma-biotech',
            'name'  => 'Pharmaceutical & Biotech',
            'icon'  => '🧬',
            'color' => '#0e7490',
            'tagline' => 'Accelerating discovery with artificial intelligence',
            'description' => 'Pharmaceutical and biotech professionals who combine deep scientific expertise with AI capability. Compressing drug discovery timelines from decades to years.',
            'image1'=> 'images/ai-brain.jpg',
            'image2'=> 'images/cyborg-hero.jpg',
            'maya_prompt' => 'You are a pharma and biotech talent specialist. Verify scientific credentials, GxP experience, and regulatory submission history.',
            'roles' => [
                ['title'=>'Drug Discovery AI Researcher','tier'=>'senior','daily_rate'=>1400,'skills'=>['Molecular ML','AlphaFold','ADMET Prediction','Target Identification']],
                ['title'=>'Clinical Data Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['CDISC','Medidata Rave','Data Validation','EDC Management']],
                ['title'=>'Regulatory Submission Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['eCTD','FDA/EMA Submissions','IND/BLA/NDA','eCRF Design']],
                ['title'=>'Bioinformatics Engineer','tier'=>'senior','daily_rate'=>1200,'skills'=>['Genomics Pipeline','Single-Cell','NGS','Python/R']],
                ['title'=>'Pharma Supply Chain Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Cold Chain','Serialization','GDP','Supply Security']],
                ['title'=>'Clinical Trial AI Coordinator','tier'=>'senior','daily_rate'=>1000,'skills'=>['Decentralized Trials','Site Management','Protocol Adherence','Data Cleaning']],
                ['title'=>'Drug Interaction Analyst','tier'=>'senior','daily_rate'=>1100,'skills'=>['PK/PD Modeling','Computational Chemistry','Safety Profile','FDA Guidance']],
                ['title'=>'Quality Assurance Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['GMP/GCP/GLP','CAPA','Audit Management','Validation']],
                ['title'=>'Pharmacovigilance Specialist','tier'=>'senior','daily_rate'=>950,'skills'=>['ICSR Processing','Signal Detection','PSUR','Oracle Argus']],
                ['title'=>'Biotech Product Manager','tier'=>'senior','daily_rate'=>1200,'skills'=>['Development Strategy','Regulatory Pathway','Market Access','KOL Management']],
            ],
        ],

        [
            'id'    => 'telecommunications',
            'name'  => 'Telecommunications',
            'icon'  => '📡',
            'color' => '#1d4ed8',
            'tagline' => 'Connecting humanity at the speed of light',
            'description' => 'Telecom engineers and strategists building next-generation network infrastructure. 5G, satellite communications, and the IoT networks of tomorrow.',
            'image1'=> 'images/hero2.jpg',
            'image2'=> 'images/robots-working.jpg',
            'maya_prompt' => 'You are a telecommunications talent specialist. Evaluate 3GPP standards knowledge, network architecture credentials, and vendor certifications.',
            'roles' => [
                ['title'=>'5G Network Architect','tier'=>'senior','daily_rate'=>1300,'skills'=>['5G NR','Core Network','Network Slicing','RAN Architecture']],
                ['title'=>'Network AI Engineer','tier'=>'senior','daily_rate'=>1200,'skills'=>['AIOps','Network Automation','Anomaly Detection','Self-Healing Networks']],
                ['title'=>'Telecom Data Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['Network Analytics','KPI Design','Churn Prediction','Usage Analytics']],
                ['title'=>'Network Operations Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['NOC Management','Incident Response','SLA Management','Multi-Vendor']],
                ['title'=>'OSS/BSS Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['TM Forum','Service Provisioning','Revenue Management','System Integration']],
                ['title'=>'IoT Network Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['LPWAN','NB-IoT','eSIM','Device Management']],
                ['title'=>'Satellite Communications Specialist','tier'=>'senior','daily_rate'=>1200,'skills'=>['GEO/LEO/MEO','Link Budget','Ground Segment','Orbital Management']],
                ['title'=>'Telecom Product Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Network Services','B2B/B2C Products','5G Use Cases','CPE Management']],
                ['title'=>'Radio Access Network Engineer','tier'=>'senior','daily_rate'=>1150,'skills'=>['RAN Optimization','Antenna Design','Interference Management','Drive Testing']],
                ['title'=>'Spectrum Management Specialist','tier'=>'senior','daily_rate'=>1050,'skills'=>['ITU Coordination','Regulatory Filings','Spectrum Planning','Interference Analysis']],
            ],
        ],

        [
            'id'    => 'agriculture-foodtech',
            'name'  => 'Agriculture & Food Tech',
            'icon'  => '🌾',
            'color' => '#65a30d',
            'tagline' => 'Feeding the world with artificial intelligence',
            'description' => 'AgriTech specialists who apply AI to the world\'s most critical challenge: feeding 10 billion people. Precision farming, food safety, and supply chain intelligence.',
            'image1'=> 'images/testimonial-bg.jpg',
            'image2'=> 'images/hero2.jpg',
            'maya_prompt' => 'You are an agriculture and food technology talent specialist. Evaluate agronomic knowledge, IoT deployment experience, and food safety credentials.',
            'roles' => [
                ['title'=>'AgriTech AI Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Crop Modeling','Computer Vision for Crops','Yield Prediction','Climate Data']],
                ['title'=>'Precision Farming Engineer','tier'=>'senior','daily_rate'=>1050,'skills'=>['GPS/RTK','Variable Rate Technology','Drones','Soil Sensors']],
                ['title'=>'Food Safety Data Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['HACCP','Traceability','Regulatory Compliance','Contamination Detection']],
                ['title'=>'Vertical Farming Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['CEA','Hydroponics/Aeroponics','Lighting Systems','Automation']],
                ['title'=>'Agricultural Supply Chain Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['Cold Chain','Commodity Procurement','Food Miles','Supplier Management']],
                ['title'=>'Crop Yield Optimization Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['ML Forecasting','Remote Sensing','Agronomic Modeling','Input Optimization']],
                ['title'=>'Agricultural Drone Operator','tier'=>'mid','daily_rate'=>700,'skills'=>['UAV Operations','Multispectral Imaging','NDVI Analysis','Regulatory Compliance']],
                ['title'=>'Soil Intelligence Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['Soil Science','IoT Sensors','Carbon Sequestration','Precision Nutrition']],
                ['title'=>'Food Traceability Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Blockchain','RFID/QR','GS1 Standards','Serialization']],
                ['title'=>'AgriTech Product Manager','tier'=>'senior','daily_rate'=>1050,'skills'=>['Farmer UX','Field Validation','Go-to-Market','Partner Channels']],
            ],
        ],

        [
            'id'    => 'transportation-mobility',
            'name'  => 'Transportation & Mobility',
            'icon'  => '🚗',
            'color' => '#7c3aed',
            'tagline' => 'The autonomous future of movement',
            'description' => 'Mobility engineers and strategists building the transportation systems of the autonomous age. AVs, fleet intelligence, and smart traffic systems.',
            'image1'=> 'images/cyborgs2.jpg',
            'image2'=> 'images/robots-working.jpg',
            'maya_prompt' => 'You are a transportation and mobility talent specialist. Evaluate autonomous systems experience, fleet management scale, and safety certification knowledge.',
            'roles' => [
                ['title'=>'Autonomous Vehicle Engineer','tier'=>'senior','daily_rate'=>1400,'skills'=>['Sensor Fusion','Perception','Path Planning','Simulation']],
                ['title'=>'Fleet Management AI Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['Telematics','Route Optimization','Predictive Maintenance','EV Fleet']],
                ['title'=>'Transportation Data Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['Movement Analytics','Demand Modeling','Origin-Destination','GIS']],
                ['title'=>'Route Optimization Engineer','tier'=>'senior','daily_rate'=>1050,'skills'=>['VRP','CVRP','Last-Mile','Dynamic Routing']],
                ['title'=>'Smart Traffic Systems Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Traffic Signal Control','V2X','ATMS','Incident Detection']],
                ['title'=>'Mobility-as-a-Service PM','tier'=>'senior','daily_rate'=>1100,'skills'=>['MaaS Platforms','Multimodal','APIs','Operator Partnerships']],
                ['title'=>'Logistics AI Coordinator','tier'=>'mid','daily_rate'=>800,'skills'=>['TMS','Carrier Management','Load Planning','Real-Time Visibility']],
                ['title'=>'Fleet Electrification Specialist','tier'=>'senior','daily_rate'=>1050,'skills'=>['EV Charging','Range Planning','TCO Modeling','Grid Impact']],
                ['title'=>'Transport Safety AI Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['SOTIF','ISO 26262','Safety Analysis','Incident Investigation']],
                ['title'=>'Multimodal Transport Planner','tier'=>'senior','daily_rate'=>1000,'skills'=>['Intermodal','Urban Planning','Demand Management','Policy']],
            ],
        ],

        [
            'id'    => 'hospitality-tourism',
            'name'  => 'Hospitality & Tourism',
            'icon'  => '✈️',
            'color' => '#0891b2',
            'tagline' => 'Unforgettable experiences powered by AI',
            'description' => 'Hospitality technologists delivering personalized luxury at scale. Revenue management, guest intelligence, and operational excellence for the world\'s premier brands.',
            'image1'=> 'images/marketing-hero.jpg',
            'image2'=> 'images/testimonial-bg.jpg',
            'maya_prompt' => 'You are a hospitality technology talent specialist. Evaluate PMS/RMS experience, guest satisfaction metrics, and revenue management track records.',
            'roles' => [
                ['title'=>'Hospitality AI Director','tier'=>'executive','daily_rate'=>1800,'skills'=>['AI Strategy','Guest Personalization','Revenue AI','Brand']],
                ['title'=>'Revenue Management Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Dynamic Pricing','RMS','Forecasting','Competitive Set']],
                ['title'=>'Guest Experience AI Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Personalization Engine','CRM','In-Stay Services','Feedback']],
                ['title'=>'Hotel Technology Manager','tier'=>'senior','daily_rate'=>950,'skills'=>['PMS Integration','IoT in Hotels','POS','Smart Room']],
                ['title'=>'Travel Demand Forecaster','tier'=>'senior','daily_rate'=>950,'skills'=>['Booking Pattern Analysis','Macro Forecasting','Event Impact','Scenario']],
                ['title'=>'Food & Beverage AI Optimizer','tier'=>'senior','daily_rate'=>900,'skills'=>['Menu Engineering','Waste Reduction','Demand Forecasting','Pricing']],
                ['title'=>'Tourism Analytics Specialist','tier'=>'senior','daily_rate'=>850,'skills'=>['Visitor Analytics','Economic Impact','Tourism Modeling','Segmentation']],
                ['title'=>'Smart Hospitality Product Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Digital Check-In','Smart Key','Guest App','Contactless']],
                ['title'=>'Event Technology Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['Event Tech Stack','AV/IT','Event Analytics','Hybrid Events']],
                ['title'=>'Concierge AI Developer','tier'=>'senior','daily_rate'=>1100,'skills'=>['NLP','Conversational AI','Local Knowledge Base','Recommendations']],
            ],
        ],

        [
            'id'    => 'construction-architecture',
            'name'  => 'Construction & Architecture',
            'icon'  => '🏗️',
            'color' => '#92400e',
            'tagline' => 'Building tomorrow\'s world with AI precision',
            'description' => 'Construction technology experts deploying AI across the built environment. BIM modeling, site safety intelligence, and digital twin construction.',
            'image1'=> 'images/staffing-dept1.jpg',
            'image2'=> 'images/robots-working.jpg',
            'maya_prompt' => 'You are a construction and architecture talent specialist. Evaluate BIM certification level, project size experience, and safety record.',
            'roles' => [
                ['title'=>'BIM Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Revit','Navisworks','ISO 19650','CDE Management']],
                ['title'=>'Construction AI Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['Computer Vision for Sites','Progress Monitoring','Safety AI','Document AI']],
                ['title'=>'Project Controls Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Primavera P6','Earned Value','Cost Control','Schedule Risk']],
                ['title'=>'Digital Twin Engineer','tier'=>'senior','daily_rate'=>1150,'skills'=>['Asset Lifecycle','IoT Integration','3D Models','Predictive Ops']],
                ['title'=>'Smart Building Architect','tier'=>'senior','daily_rate'=>1200,'skills'=>['LEED','WELL','Active Design','BAS Integration']],
                ['title'=>'Site Safety AI Officer','tier'=>'senior','daily_rate'=>1000,'skills'=>['PPE Detection','Near-Miss Prediction','Safety Analytics','Incident Prevention']],
                ['title'=>'Structural Analysis Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['FEA','Structural AI','STAAD','Code Compliance']],
                ['title'=>'Construction Data Analyst','tier'=>'mid','daily_rate'=>750,'skills'=>['Procore Analytics','Cost Benchmarking','Productivity Metrics','Quality Data']],
                ['title'=>'Prefab & Modular Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Off-Site Construction','DfMA','Lean Construction','Supply Chain']],
                ['title'=>'Real-Time Monitoring Specialist','tier'=>'senior','daily_rate'=>950,'skills'=>['IoT Sensors','Drone Monitoring','Progress Tracking','Alerts']],
            ],
        ],

        [
            'id'    => 'research-development',
            'name'  => 'Research & Development',
            'icon'  => '🔬',
            'color' => '#7c3aed',
            'tagline' => 'Discovering the future, systematically',
            'description' => 'R&D professionals who translate scientific breakthroughs into commercial reality. Technology scouting, patent strategy, and innovation program management.',
            'image1'=> 'images/ai-brain.jpg',
            'image2'=> 'images/cyborg-hero.jpg',
            'maya_prompt' => 'You are an R&D talent specialist. Evaluate publication record, patent portfolio, and technology transfer success.',
            'roles' => [
                ['title'=>'R&D Director','tier'=>'executive','daily_rate'=>2000,'skills'=>['Innovation Strategy','Portfolio Management','IP Strategy','Funding']],
                ['title'=>'Innovation Manager','tier'=>'senior','daily_rate'=>1100,'skills'=>['Design Thinking','Stage-Gate','Open Innovation','Ecosystem']],
                ['title'=>'Technology Scout','tier'=>'senior','daily_rate'=>1000,'skills'=>['Emerging Tech','Patent Landscaping','Startup Scouting','Tech Radar']],
                ['title'=>'Patent Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['Patent Search','FTO Analysis','IP Valuation','Portfolio Strategy']],
                ['title'=>'Scientific Computing Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['HPC','Simulation','Python/Julia','Data Pipeline']],
                ['title'=>'Research Program Manager','tier'=>'senior','daily_rate'=>1000,'skills'=>['Grant Writing','IRB','Publication Strategy','Collaboration']],
                ['title'=>'Competitive Intelligence Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['Patent Intelligence','Market Analysis','Technology Mapping','SWOT']],
                ['title'=>'Technology Transfer Specialist','tier'=>'senior','daily_rate'=>950,'skills'=>['Licensing','Commercialization','Spinouts','IP Negotiation']],
                ['title'=>'Lab Data Manager','tier'=>'mid','daily_rate'=>750,'skills'=>['LIMS','ELN','Data Standards','Research Data Management']],
                ['title'=>'Prototype Development Lead','tier'=>'senior','daily_rate'=>1000,'skills'=>['Rapid Prototyping','3D Printing','MVPs','Test & Iterate']],
            ],
        ],

// CONNECTS TO: aicinesynth.com
[
    'id'    => 'video-production',
    'name'  => 'Video Production & AI Cinema',
    'icon'  => '🎬',
    'color' => '#e11d48',
    'tagline' => 'Cinematic AI at the speed of thought',
    'description' => 'AI filmmakers who produce cinematic content using Kling, Runway, Veo, and Sora. From product demos to feature-length narratives. Connected to AI Cine Synth for instant generation.',
    'image1'=> 'images/marketing-hero.jpg',
    'image2'=> 'images/cyborg-hero.jpg',
    'maya_prompt' => 'You are a video production talent specialist. Evaluate prompt engineering for video AI, cinematography knowledge, and storytelling ability.',
    'roles' => [
        ['title'=>'AI Film Director','tier'=>'executive','daily_rate'=>1800,'skills'=>['Kling','Runway','Veo','Prompt Craft','Cinematography']],
        ['title'=>'Video Prompt Engineer','tier'=>'senior','daily_rate'=>950,'skills'=>['Text-to-Video','Scene Design','Style Transfer','Storyboarding']],
        ['title'=>'Motion Graphics AI Artist','tier'=>'senior','daily_rate'=>900,'skills'=>['After Effects','Lottie','AI Animation','Brand Motion']],
        ['title'=>'AI Colorist','tier'=>'senior','daily_rate'=>850,'skills'=>['Color Grading','LUT Design','Mood Setting','Visual Consistency']],
        ['title'=>'Vertical Video Specialist','tier'=>'mid','daily_rate'=>650,'skills'=>['TikTok','Reels','Shorts','Mobile-First','Engagement']],
        ['title'=>'AI Video Editor','tier'=>'mid','daily_rate'=>700,'skills'=>['Cut Detection','Scene Sequencing','Audio Sync','Export Optimization']],
        ['title'=>'Product Demo Producer','tier'=>'senior','daily_rate'=>950,'skills'=>['SaaS Demos','Explainers','Walkthrough','Conversion']],
        ['title'=>'Documentary AI Producer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Narrative Arc','Research','Interview AI','Archive Processing']],
        ['title'=>'Music Video AI Director','tier'=>'senior','daily_rate'=>1000,'skills'=>['Visual Rhythm','Beat Sync','Style Matching','Creative Direction']],
        ['title'=>'AI VFX Artist','tier'=>'senior','daily_rate'=>1200,'skills'=>['Compositing','Particle FX','Environment Gen','Physics Sim']],
    ],
],

// ── 37 VOICE & AUDIO AI ────────────────────────────────────────────
// THE "22 LABS" — Instead of paying ElevenLabs, we ARE the voice platform
[
    'id'    => 'voice-audio-ai',
    'name'  => 'Voice & Audio AI',
    'icon'  => '🎙️',
    'color' => '#8b5cf6',
    'tagline' => 'The 22Labs of the empire — our own voice platform',
    'description' => 'AI voice engineers who build TTS, voice cloning, audio processing, and podcast automation. Instead of paying ElevenLabs, we built our own. Now we sell it.',
    'image1'=> 'images/ai-brain.jpg',
    'image2'=> 'images/staffing-dept1.jpg',
    'maya_prompt' => 'You are a voice/audio AI specialist. Evaluate TTS quality, voice cloning ethics, audio engineering depth.',
    'roles' => [
        ['title'=>'TTS Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Text-to-Speech','VITS','Tacotron','Voice Quality']],
        ['title'=>'Voice Cloning Specialist','tier'=>'senior','daily_rate'=>1100,'skills'=>['Speaker Embedding','Few-Shot Voice','Ethics','Consent']],
        ['title'=>'Audio AI Engineer','tier'=>'senior','daily_rate'=>950,'skills'=>['Whisper','Speech-to-Text','Noise Reduction','Diarization']],
        ['title'=>'Podcast Automation Engineer','tier'=>'mid','daily_rate'=>750,'skills'=>['Auto-Editing','Show Notes','Transcription','Distribution']],
        ['title'=>'Music Generation Specialist','tier'=>'senior','daily_rate'=>1000,'skills'=>['Suno','MusicLM','Composition','Licensing']],
        ['title'=>'Voice UX Designer','tier'=>'senior','daily_rate'=>900,'skills'=>['Conversational AI','Alexa/Google','Voice Personas','Dialog Flow']],
        ['title'=>'Audio Quality Engineer','tier'=>'mid','daily_rate'=>700,'skills'=>['Mastering','Normalization','Format Optimization','Streaming']],
        ['title'=>'Sound Design AI Artist','tier'=>'senior','daily_rate'=>950,'skills'=>['SFX Generation','Foley AI','Ambient','Spatial Audio']],
    ],
],

// ── 38 PROMPT ENGINEERING & AI CONSULTING ──────────────────────────
[
    'id'    => 'prompt-engineering',
    'name'  => 'Prompt Engineering & AI Consulting',
    'icon'  => '✍️',
    'color' => '#06b6d4',
    'tagline' => 'The art of talking to machines',
    'description' => 'Elite prompt engineers who maximize AI output quality. System prompt design, chain-of-thought optimization, RAG architecture, and enterprise AI strategy consulting.',
    'image1'=> 'images/robots-working.jpg',
    'image2'=> 'images/ai-brain.jpg',
    'maya_prompt' => 'You are a prompt engineering talent specialist. Evaluate prompt design skill, chain-of-thought mastery, and LLM knowledge.',
    'roles' => [
        ['title'=>'Chief AI Prompt Architect','tier'=>'executive','daily_rate'=>2000,'skills'=>['System Prompts','Multi-Agent','Enterprise AI Strategy']],
        ['title'=>'Senior Prompt Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Chain-of-Thought','Few-Shot','Constitutional AI','Evaluation']],
        ['title'=>'RAG Architect','tier'=>'senior','daily_rate'=>1200,'skills'=>['Retrieval','Embedding','Vector DB','Chunk Strategy']],
        ['title'=>'AI Workflow Designer','tier'=>'senior','daily_rate'=>950,'skills'=>['LangChain','Agent Design','Tool Use','Orchestration']],
        ['title'=>'LLM Fine-Tuning Engineer','tier'=>'senior','daily_rate'=>1300,'skills'=>['LoRA','QLoRA','Dataset Curation','Evaluation']],
        ['title'=>'AI Strategy Consultant','tier'=>'executive','daily_rate'=>2500,'skills'=>['ROI Analysis','AI Readiness','Change Management','Vendor Selection']],
        ['title'=>'Prompt QA Analyst','tier'=>'mid','daily_rate'=>700,'skills'=>['Output Testing','Hallucination Detection','Bias Audit','Benchmarking']],
        ['title'=>'Multi-Modal Prompt Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Vision','Audio','Video Prompts','Cross-Modal']],
        ['title'=>'AI Ethics Prompt Designer','tier'=>'senior','daily_rate'=>950,'skills'=>['Safety Alignment','Red-Teaming','Guardrails','Constitutional']],
    ],
],

// ── 39 AI SAFETY & ALIGNMENT ──────────────────────────────────────
[
    'id'    => 'ai-safety',
    'name'  => 'AI Safety & Alignment',
    'icon'  => '⚖️',
    'color' => '#0d9488',
    'tagline' => 'Building AI that serves humanity',
    'description' => 'Safety researchers and alignment engineers who ensure AI systems are trustworthy, unbiased, and compliant. Red-teaming, interpretability, and governance.',
    'image1'=> 'images/staffing-security.jpg',
    'image2'=> 'images/ai-brain.jpg',
    'maya_prompt' => 'You are an AI safety talent specialist. Evaluate alignment research, red-teaming experience, and policy knowledge.',
    'roles' => [
        ['title'=>'AI Alignment Researcher','tier'=>'executive','daily_rate'=>2000,'skills'=>['RLHF','Constitutional AI','Value Alignment','Interpretability']],
        ['title'=>'Red Team Lead','tier'=>'senior','daily_rate'=>1400,'skills'=>['Adversarial Testing','Jailbreak Detection','Attack Surface','Mitigation']],
        ['title'=>'AI Governance Officer','tier'=>'executive','daily_rate'=>1800,'skills'=>['EU AI Act','Policy Design','Risk Framework','Board Advisory']],
        ['title'=>'Bias Auditor','tier'=>'senior','daily_rate'=>1000,'skills'=>['Fairness Metrics','Demographic Parity','Dataset Bias','Remediation']],
        ['title'=>'AI Compliance Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['GDPR','CCPA','AI Regulations','Documentation']],
        ['title'=>'Interpretability Engineer','tier'=>'senior','daily_rate'=>1200,'skills'=>['SHAP','LIME','Attention Maps','Feature Attribution']],
        ['title'=>'Safety Evaluation Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Benchmark Design','Automated Eval','Regression Testing','Safety Scoring']],
    ],
],

// ── 40 DEVELOPER RELATIONS & COMMUNITY ────────────────────────────
[
    'id'    => 'devrel-community',
    'name'  => 'Developer Relations & Community',
    'icon'  => '👩‍💻',
    'color' => '#f97316',
    'tagline' => 'Building the builders',
    'description' => 'DevRel professionals who grow developer communities, create documentation, run hackathons, and build developer advocacy programs. Critical for API-first products like OpenCrest.',
    'image1'=> 'images/hero-ai-team.jpg',
    'image2'=> 'images/robots-working.jpg',
    'maya_prompt' => 'You are a DevRel talent specialist. Evaluate community building, technical writing, and developer empathy.',
    'roles' => [
        ['title'=>'Head of Developer Relations','tier'=>'executive','daily_rate'=>1800,'skills'=>['Community Strategy','Developer Journey','Ecosystem Growth']],
        ['title'=>'Developer Advocate','tier'=>'senior','daily_rate'=>1000,'skills'=>['Technical Talks','Demo Building','Social Media','Conference']],
        ['title'=>'Technical Writer','tier'=>'mid','daily_rate'=>700,'skills'=>['API Docs','Tutorials','Guides','Code Samples']],
        ['title'=>'Community Manager','tier'=>'mid','daily_rate'=>650,'skills'=>['Discord','Slack','Forum','Engagement Metrics']],
        ['title'=>'Hackathon Organizer','tier'=>'senior','daily_rate'=>900,'skills'=>['Event Planning','Sponsorship','Judging','Prize Design']],
        ['title'=>'SDK Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Python','JavaScript','Go','SDK Design','Versioning']],
        ['title'=>'Developer Experience Engineer','tier'=>'senior','daily_rate'=>1050,'skills'=>['Onboarding','CLI Tools','Playground','Error Messages']],
        ['title'=>'Open Source Maintainer','tier'=>'senior','daily_rate'=>900,'skills'=>['GitHub','PR Review','Release Management','Community Standards']],
    ],
],

// ── 41 CLIMATE TECH & ESG ─────────────────────────────────────────
[
    'id'    => 'climate-esg',
    'name'  => 'Climate Tech & ESG',
    'icon'  => '🌍',
    'color' => '#16a34a',
    'tagline' => 'AI for planetary survival',
    'description' => 'Climate technology specialists using AI for carbon tracking, ESG reporting, renewable energy optimization, and sustainability compliance. Growing demand from Fortune 500.',
    'image1'=> 'images/hero2.jpg',
    'image2'=> 'images/staffing-dept1.jpg',
    'maya_prompt' => 'You are a climate tech talent specialist. Evaluate carbon methodology, ESG framework mastery, and sustainability metrics.',
    'roles' => [
        ['title'=>'Carbon Accounting AI Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['GHG Protocol','Scope 1-3','Carbon API','Offset Verification']],
        ['title'=>'ESG Reporting Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['TCFD','CSRD','GRI','Data Collection']],
        ['title'=>'Climate Risk Modeler','tier'=>'senior','daily_rate'=>1200,'skills'=>['Climate Scenarios','Physical Risk','Transition Risk','VaR']],
        ['title'=>'Renewable Energy Optimizer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Solar Forecasting','Wind Analytics','Grid Balancing','Storage']],
        ['title'=>'Sustainability Data Scientist','tier'=>'senior','daily_rate'=>1000,'skills'=>['LCA','Material Flow','Impact Assessment','Footprint']],
        ['title'=>'Green Finance Analyst','tier'=>'senior','daily_rate'=>1050,'skills'=>['Green Bonds','Impact Investing','Taxonomy Alignment','Due Diligence']],
        ['title'=>'Circular Economy Strategist','tier'=>'senior','daily_rate'=>950,'skills'=>['Waste Reduction','Reuse Systems','Supply Loop','Lifecycle']],
    ],
],

// ── 42 TRANSLATION & LOCALIZATION ─────────────────────────────────
// Maya speaks 20+ languages — this agency monetizes that
[
    'id'    => 'translation-localization',
    'name'  => 'Translation & Localization',
    'icon'  => '🌐',
    'color' => '#2563eb',
    'tagline' => 'Every language. Every market. Instantly.',
    'description' => 'AI translation and localization specialists powered by Maya\'s multilingual brain. Translate apps, websites, documents, and marketing across 20+ languages simultaneously.',
    'image1'=> 'images/hero-ai-team.jpg',
    'image2'=> 'images/testimonial-bg.jpg',
    'maya_prompt' => 'You are a translation talent specialist. Evaluate language pair mastery, cultural sensitivity, and localization depth.',
    'roles' => [
        ['title'=>'AI Translation Director','tier'=>'executive','daily_rate'=>1600,'skills'=>['Multi-Language Strategy','Quality Standards','Market Expansion']],
        ['title'=>'Neural MT Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Transformer Models','BLEU Score','Custom Training','Low-Resource']],
        ['title'=>'Localization Manager','tier'=>'senior','daily_rate'=>900,'skills'=>['i18n','l10n','Cultural Adaptation','Date/Currency/Units']],
        ['title'=>'Transcreation Specialist','tier'=>'senior','daily_rate'=>950,'skills'=>['Marketing Translation','Brand Voice','Cultural Nuance','Copywriting']],
        ['title'=>'Subtitle & Caption AI','tier'=>'mid','daily_rate'=>650,'skills'=>['SRT','VTT','Timing','Whisper','Auto-Sync']],
        ['title'=>'Legal Translation AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Contract Translation','Regulatory','Certified','Terminology']],
        ['title'=>'Website Localization Engineer','tier'=>'mid','daily_rate'=>750,'skills'=>['CMS Integration','SEO Translation','Hreflang','RTL Support']],
        ['title'=>'Multilingual QA Tester','tier'=>'mid','daily_rate'=>600,'skills'=>['String Testing','Layout Check','Cultural Review','Regression']],
    ],
],

// ── 43 RECRUITMENT & HR TECH AI ───────────────────────────────────
// Meta: The staffing agency has a staffing agency FOR staffing
[
    'id'    => 'recruitment-hrtech',
    'name'  => 'Recruitment & HR Tech AI',
    'icon'  => '🎯',
    'color' => '#c026d3',
    'tagline' => 'AI that recruits better than humans',
    'description' => 'AI recruitment specialists who automate sourcing, screening, matching, and onboarding. The meta-agency: we use AI to find people who need AI staff.',
    'image1'=> 'images/cyborgs1.jpg',
    'image2'=> 'images/hero-ai-team.jpg',
    'maya_prompt' => 'You are a recruitment AI specialist. Evaluate ATS knowledge, sourcing methodology, and candidate experience design.',
    'roles' => [
        ['title'=>'AI Talent Sourcer','tier'=>'senior','daily_rate'=>900,'skills'=>['LinkedIn AI','Boolean Search','Pipeline Building','Passive Candidates']],
        ['title'=>'Resume Screening AI','tier'=>'mid','daily_rate'=>650,'skills'=>['NLP Parsing','Skill Extraction','Bias Detection','Ranking']],
        ['title'=>'Candidate Experience Designer','tier'=>'senior','daily_rate'=>850,'skills'=>['Journey Mapping','Communication AI','Feedback Loop','NPS']],
        ['title'=>'ATS Integration Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['Greenhouse','Lever','Workday','API Integration']],
        ['title'=>'Interview AI Specialist','tier'=>'senior','daily_rate'=>950,'skills'=>['Structured Interview','Assessment Design','Video Analysis','Bias Audit']],
        ['title'=>'Employer Brand AI','tier'=>'senior','daily_rate'=>900,'skills'=>['Glassdoor','LinkedIn Presence','Careers Page','Content Strategy']],
        ['title'=>'Workforce Planning Analyst','tier'=>'senior','daily_rate'=>1000,'skills'=>['Headcount Modeling','Attrition Prediction','Skills Gap','Succession']],
        ['title'=>'Onboarding Automation Engineer','tier'=>'mid','daily_rate'=>700,'skills'=>['Workflow Design','Document AI','Training Path','Day-1 Experience']],
    ],
],

// ── 44 SPORTS & GAMING ANALYTICS ──────────────────────────────────
[
    'id'    => 'sports-gaming',
    'name'  => 'Sports & Gaming Analytics',
    'icon'  => '🎮',
    'color' => '#ea580c',
    'tagline' => 'Data-driven victories',
    'description' => 'AI analytics for sports performance, esports strategy, gaming economy design, and player engagement. From match prediction to in-game economy balancing.',
    'image1'=> 'images/cyborg-hero.jpg',
    'image2'=> 'images/marketing-hero.jpg',
    'maya_prompt' => 'You are a sports/gaming analytics specialist. Evaluate statistical modeling, game theory, and player behavior analysis.',
    'roles' => [
        ['title'=>'Sports Analytics Director','tier'=>'executive','daily_rate'=>1800,'skills'=>['Performance Modeling','Injury Prediction','Draft Analytics','Salary Cap']],
        ['title'=>'Esports Strategy Analyst','tier'=>'senior','daily_rate'=>900,'skills'=>['Meta Analysis','Team Composition','Win Probability','VOD Review']],
        ['title'=>'Game Economy Designer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Token Economics','Reward Systems','Inflation Control','Monetization']],
        ['title'=>'Player Behavior Analyst','tier'=>'senior','daily_rate'=>950,'skills'=>['Engagement Metrics','Churn Prediction','Cohort Analysis','LTV']],
        ['title'=>'Computer Vision Sports Analyst','tier'=>'senior','daily_rate'=>1200,'skills'=>['Player Tracking','Pose Estimation','Formation Analysis','Broadcast']],
        ['title'=>'Betting Algorithm Engineer','tier'=>'senior','daily_rate'=>1300,'skills'=>['Odds Modeling','Market Making','Risk Management','Real-Time']],
        ['title'=>'Fantasy Sports AI','tier'=>'mid','daily_rate'=>750,'skills'=>['Projection Models','Lineup Optimization','DFS','Correlation']],
        ['title'=>'Game AI Developer','tier'=>'senior','daily_rate'=>1100,'skills'=>['NPC Behavior','Pathfinding','Difficulty Scaling','Procedural Gen']],
    ],
],

// ── 45 INSURANCE & RISK AI ────────────────────────────────────────
[
    'id'    => 'insurance-risk',
    'name'  => 'Insurance & Risk AI',
    'icon'  => '🏛️',
    'color' => '#0f766e',
    'tagline' => 'Quantifying uncertainty with precision',
    'description' => 'AI-powered insurance underwriting, claims processing, actuarial modeling, and risk assessment. Automating the trillion-dollar insurance industry.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are an insurance/risk AI specialist. Evaluate actuarial knowledge, claims automation experience, and regulatory compliance.',
    'roles' => [
        ['title'=>'AI Underwriting Engine','tier'=>'executive','daily_rate'=>1800,'skills'=>['Risk Scoring','Policy Pricing','Portfolio Analysis','Reinsurance']],
        ['title'=>'Claims Automation Engineer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Document AI','Fraud Detection','Auto-Adjudication','STP']],
        ['title'=>'Actuarial AI Modeler','tier'=>'senior','daily_rate'=>1300,'skills'=>['Loss Modeling','Mortality Tables','Reserve Estimation','Catastrophe']],
        ['title'=>'Fraud Detection Specialist','tier'=>'senior','daily_rate'=>1200,'skills'=>['Anomaly Detection','Network Analysis','Pattern Recognition','SIU']],
        ['title'=>'Insurance Chatbot Engineer','tier'=>'mid','daily_rate'=>750,'skills'=>['Policy Q&A','Claims Filing','Quote Generation','Renewal']],
        ['title'=>'Parametric Insurance Designer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Index-Based','Weather Data','IoT Triggers','Smart Contracts']],
        ['title'=>'Risk Analytics Engineer','tier'=>'senior','daily_rate'=>1050,'skills'=>['VaR','Monte Carlo','Stress Testing','Scenario Analysis']],
        ['title'=>'Regulatory Compliance AI','tier'=>'senior','daily_rate'=>950,'skills'=>['Solvency II','NAIC','State Filing','Rate Review']],
    ],
],
[
    'id'    => 'automotive-mobility',
    'name'  => 'Automotive & Mobility AI',
    'icon'  => '🚗',
    'color' => '#ef4444',
    'tagline' => 'Software-defined vehicles, autonomous fleets',
    'description' => 'AI for autonomous driving, vehicle telematics, in-car voice assistants, OTA update engineering, and predictive fleet maintenance.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are an automotive AI specialist. Evaluate ADAS, sensor fusion, drive-by-wire, and OEM experience.',
    'roles' => [
        ['title'=>'ADAS Engineer','tier'=>'executive','daily_rate'=>1900,'skills'=>['Sensor Fusion','Lidar','Radar','Level 3+ Autonomy']],
        ['title'=>'Autonomous Fleet Orchestrator','tier'=>'senior','daily_rate'=>1500,'skills'=>['Mission Planning','Swap Routing','OTA','Traffic Optimization']],
        ['title'=>'In-Car Voice AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Wake-word','Low-Latency ASR','Automotive NLU','Offline Fallback']],
        ['title'=>'Telematics ML Engineer','tier'=>'senior','daily_rate'=>1050,'skills'=>['CAN Bus','UDS','Anomaly Detection','Driver Scoring']],
        ['title'=>'Simulation QA (Autonomy)','tier'=>'senior','daily_rate'=>1200,'skills'=>['CARLA','LGSVL','Scenario Generation','Edge Cases']],
        ['title'=>'Battery Mgmt AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['SOC Estimation','Thermal','Fast-Charge','Lifetime Prediction']],
        ['title'=>'V2X Protocol Engineer','tier'=>'senior','daily_rate'=>1000,'skills'=>['DSRC','C-V2X','Intersection Intelligence','Edge Compute']],
        ['title'=>'Fleet Routing Optimizer','tier'=>'mid','daily_rate'=>800,'skills'=>['VRP','TSP','Dynamic Dispatch','Last-Mile']],
    ],
],
[
    'id'    => 'aviation-drones',
    'name'  => 'Aviation & Drone AI',
    'icon'  => '🛩️',
    'color' => '#0ea5e9',
    'tagline' => 'From hobbyist drones to commercial fleets',
    'description' => 'Flight path optimization, autonomous drone pilots, swarm coordination, air traffic AI, and airline revenue optimization.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are an aviation/drone AI specialist. Evaluate flight systems, Part 107, BVLOS, and airspace integration.',
    'roles' => [
        ['title'=>'Autonomous Drone Pilot AI','tier'=>'senior','daily_rate'=>1400,'skills'=>['Computer Vision','SLAM','Path Planning','FAA Part 107']],
        ['title'=>'Drone Swarm Coordinator','tier'=>'executive','daily_rate'=>1900,'skills'=>['Multi-Agent','Collision Avoidance','Formation','Hive Logic']],
        ['title'=>'Air Traffic AI','tier'=>'executive','daily_rate'=>2100,'skills'=>['4D Trajectory','UAS Traffic Management','Integration','Safety Cases']],
        ['title'=>'Flight Operations Optimizer','tier'=>'senior','daily_rate'=>1200,'skills'=>['Fuel Burn','Route Slotting','Crew Scheduling','Turn Reduction']],
        ['title'=>'Revenue Management AI','tier'=>'senior','daily_rate'=>1300,'skills'=>['Dynamic Pricing','Load Factor','Ancillary','Connection Banks']],
        ['title'=>'Predictive Maintenance (Aero)','tier'=>'senior','daily_rate'=>1400,'skills'=>['Engine Health','APU','NDT','FAA Records']],
        ['title'=>'Drone Inspection AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Infra Inspection','Defect Detection','Roof/Tower','Reporting']],
        ['title'=>'BVLOS Operations Lead','tier'=>'executive','daily_rate'=>1700,'skills'=>['Waiver Authoring','DAA','Risk Assessment','Commercial Ops']],
    ],
],
[
    'id'    => 'wellness-mental-health',
    'name'  => 'Wellness & Mental Health AI',
    'icon'  => '🧘',
    'color' => '#8b5cf6',
    'tagline' => 'AI companions for the human condition',
    'description' => 'AI therapy assistants, mood journaling, mindfulness coaches, crisis line augmentation, sleep & stress optimization.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are a wellness/mental-health AI specialist. Evaluate empathy modeling, HIPAA, crisis escalation, and clinical partnerships.',
    'roles' => [
        ['title'=>'AI Therapy Companion','tier'=>'senior','daily_rate'=>1400,'skills'=>['CBT','DBT','Empathy Modeling','HIPAA']],
        ['title'=>'Mood Journaling AI','tier'=>'mid','daily_rate'=>750,'skills'=>['Affect Detection','Patterns','Private Local','Daily Prompts']],
        ['title'=>'Mindfulness Coach AI','tier'=>'mid','daily_rate'=>700,'skills'=>['Guided Meditation','Breath Work','Biofeedback','Loop Personalization']],
        ['title'=>'Crisis Line Augmentor','tier'=>'executive','daily_rate'=>1800,'skills'=>['Risk Triage','Hotline Integration','Escalation','QA']],
        ['title'=>'Sleep Optimization AI','tier'=>'senior','daily_rate'=>1000,'skills'=>['Chronotype','Wearables','Light Therapy','Habit Loops']],
        ['title'=>'Corporate Wellness AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['EAP Integration','Burnout Prediction','Privacy-First','ROI']],
        ['title'=>'Psychedelic-Assisted Prep AI','tier'=>'senior','daily_rate'=>1300,'skills'=>['Set & Setting','Integration','MAPS Protocols','Safety Nets']],
        ['title'=>'Addiction Recovery AI','tier'=>'senior','daily_rate'=>1200,'skills'=>['Relapse Prediction','Sponsor Match','12-Step Complement','Recovery Tracking']],
    ],
],
[
    'id'    => 'culinary-food-science',
    'name'  => 'Culinary & Food Science AI',
    'icon'  => '🍴',
    'color' => '#f59e0b',
    'tagline' => 'Michelin-level AI for kitchens and labs',
    'description' => 'AI menu design, recipe generation, food-science ingredient optimization, restaurant revenue AI, and alternative-protein R&D.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are a culinary/food-science AI specialist. Evaluate flavor pairing, nutrition modeling, and restaurant economics.',
    'roles' => [
        ['title'=>'AI Executive Chef','tier'=>'executive','daily_rate'=>1600,'skills'=>['Menu Design','Flavor Pairing','Plating AI','Seasonality']],
        ['title'=>'Recipe Generation Engine','tier'=>'senior','daily_rate'=>1100,'skills'=>['Constraints','Nutrition','Cost/Plate','Yield Scaling']],
        ['title'=>'Food Science R&D AI','tier'=>'executive','daily_rate'=>1900,'skills'=>['Emulsions','Fermentation','Mouthfeel','Shelf-Life']],
        ['title'=>'Alt-Protein Engineer','tier'=>'executive','daily_rate'=>2100,'skills'=>['Cellular Ag','Mycelium','Precision Fermentation','Formulation']],
        ['title'=>'Restaurant Revenue AI','tier'=>'senior','daily_rate'=>1200,'skills'=>['Table Mix','Menu Engineering','Labor','Food Cost']],
        ['title'=>'Wine & Beverage AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Pairing','Cellar Optimization','Vintage Scoring','BYO Programs']],
        ['title'=>'Personalized Nutrition AI','tier'=>'senior','daily_rate'=>1300,'skills'=>['CGM Integration','Biomarkers','Meal Planning','Micronutrients']],
        ['title'=>'Ghost Kitchen Orchestrator','tier'=>'senior','daily_rate'=>1000,'skills'=>['Multi-Brand','Demand Forecast','Driver Batching','Packaging']],
    ],
],
[
    'id'    => 'fashion-luxury',
    'name'  => 'Fashion & Luxury AI',
    'icon'  => '👗',
    'color' => '#ec4899',
    'tagline' => 'Couture intelligence at scale',
    'description' => 'AI fashion design, virtual try-on, demand forecasting, sustainable materials, and luxury-brand personalization.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are a fashion/luxury AI specialist. Evaluate fashion-tech, virtual try-on, and luxury brand experience.',
    'roles' => [
        ['title'=>'AI Fashion Designer','tier'=>'executive','daily_rate'=>1800,'skills'=>['Sketch-to-Pattern','Fabric AI','Trend Synthesis','Runway']],
        ['title'=>'Virtual Try-On Engineer','tier'=>'senior','daily_rate'=>1300,'skills'=>['Body Reconstruction','Cloth Simulation','AR Filters','Metaverse']],
        ['title'=>'Demand Forecasting AI','tier'=>'senior','daily_rate'=>1200,'skills'=>['SKU Planning','Returns Modeling','Seasonality','Markdown']],
        ['title'=>'Sustainable Materials AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Bio-Materials','LCA','Recycled Fibers','Dye Chemistry']],
        ['title'=>'Luxury Personalization AI','tier'=>'executive','daily_rate'=>1700,'skills'=>['Clienteling','Bespoke','White-Glove','CRM Orchestration']],
        ['title'=>'Runway Show Producer AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Casting','Lighting','Music Sync','Live Stream']],
        ['title'=>'Counterfeit Detection AI','tier'=>'senior','daily_rate'=>1050,'skills'=>['Image Forensics','Blockchain Authentication','Authenticity DB','Resale Platforms']],
        ['title'=>'Fashion Search & Discovery','tier'=>'mid','daily_rate'=>800,'skills'=>['Visual Search','Style Similarity','Cross-Brand','Personal Stylist']],
    ],
],
[
    'id'    => 'arts-performing-music',
    'name'  => 'Arts, Music & Performing Arts AI',
    'icon'  => '🎭',
    'color' => '#a855f7',
    'tagline' => 'Where art meets AI orchestration',
    'description' => 'AI for music composition, theater staging, concert production, DAW plugins, choreography synthesis, and gallery curation.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are an arts/music AI specialist. Evaluate composition, live sound, choreography, and gallery curation.',
    'roles' => [
        ['title'=>'AI Music Composer','tier'=>'executive','daily_rate'=>1600,'skills'=>['Orchestration','Stems','Mastering','Film Score']],
        ['title'=>'DAW Plugin AI Engineer','tier'=>'senior','daily_rate'=>1300,'skills'=>['Ableton','Pro Tools','VST/AU','Real-Time DSP']],
        ['title'=>'Theater Staging AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Blocking','Lighting Cues','Sound Design','Rigging Safety']],
        ['title'=>'Choreography Synth AI','tier'=>'senior','daily_rate'=>1200,'skills'=>['Motion Capture','Dance Style Transfer','Ensemble','Beats']],
        ['title'=>'Concert Production AI','tier'=>'senior','daily_rate'=>1300,'skills'=>['Rig Design','FOH Mix','Lighting Programming','Pyro']],
        ['title'=>'Gallery Curator AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Collection Analysis','Exhibit Narrative','Provenance','Pricing']],
        ['title'=>'Voice Acting AI Director','tier'=>'senior','daily_rate'=>1050,'skills'=>['Casting','Performance Direction','Multilingual','Emotional Arc']],
        ['title'=>'Opera & Ballet AI','tier'=>'senior','daily_rate'=>1400,'skills'=>['Score Analysis','Historical Accuracy','Staging','Orchestra Balance']],
    ],
],
[
    'id'    => 'event-production',
    'name'  => 'Events & Production AI',
    'icon'  => '🎪',
    'color' => '#14b8a6',
    'tagline' => 'From weddings to world summits',
    'description' => 'AI event planning, venue optimization, attendee personalization, logistics AI, and crowd-flow safety.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are an events/production AI specialist. Evaluate logistics, hybrid formats, and attendee experience.',
    'roles' => [
        ['title'=>'AI Event Architect','tier'=>'executive','daily_rate'=>1500,'skills'=>['Theme Design','Timeline','Vendor Orchestration','Budget']],
        ['title'=>'Venue Optimization AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Layout','Crowd Flow','Safety','Catering Zones']],
        ['title'=>'Attendee Personalization','tier'=>'senior','daily_rate'=>1000,'skills'=>['Agenda Matching','Networking AI','Gifting','Sessions']],
        ['title'=>'Hybrid Event Producer','tier'=>'senior','daily_rate'=>1200,'skills'=>['Streaming','Chat Moderation','AV Sync','Remote Presenters']],
        ['title'=>'Ticketing Fraud Detector','tier'=>'senior','daily_rate'=>950,'skills'=>['Bot Detection','Resale Monitoring','ID Verification','Dynamic Limits']],
        ['title'=>'Live Translation AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Real-Time Captions','Dubbing','Sign Language','Accent Bias Control']],
        ['title'=>'Post-Event Analytics AI','tier'=>'mid','daily_rate'=>750,'skills'=>['ROI','Satisfaction','Content Repurposing','Lead Scoring']],
        ['title'=>'Wedding Planner AI','tier'=>'senior','daily_rate'=>950,'skills'=>['Vendor Match','Budget','Day-Of Ops','Cultural Customs']],
    ],
],
[
    'id'    => 'nonprofit-philanthropy',
    'name'  => 'Nonprofit & Philanthropy AI',
    'icon'  => '🤝',
    'color' => '#06b6d4',
    'tagline' => 'Scale impact, not overhead',
    'description' => 'AI donor matching, grant-writing, program impact measurement, volunteer orchestration, and disaster response.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are a nonprofit/philanthropy AI specialist. Evaluate 501(c)(3) ops, grants, and impact measurement.',
    'roles' => [
        ['title'=>'AI Grant Writer','tier'=>'senior','daily_rate'=>1100,'skills'=>['LOI','Budget Narratives','Logic Models','Federal RFPs']],
        ['title'=>'Donor Matching AI','tier'=>'senior','daily_rate'=>1050,'skills'=>['Wealth Screening','Affinity','Stewardship','Moves Mgmt']],
        ['title'=>'Impact Measurement AI','tier'=>'senior','daily_rate'=>1200,'skills'=>['Theory of Change','M&E','GIIRS','SROI']],
        ['title'=>'Volunteer Orchestrator','tier'=>'mid','daily_rate'=>700,'skills'=>['Skill Matching','Shift Mgmt','Background Checks','Recognition']],
        ['title'=>'Disaster Response AI','tier'=>'executive','daily_rate'=>1800,'skills'=>['Rapid Assessment','Resource Routing','Multi-Agency','Relief Chain']],
        ['title'=>'Fundraising Email AI','tier'=>'mid','daily_rate'=>650,'skills'=>['Appeal Writing','Segmentation','A/B','Retention Flows']],
        ['title'=>'Nonprofit Accounting AI','tier'=>'senior','daily_rate'=>1050,'skills'=>['Fund Accounting','990','Audit Prep','GAAP']],
        ['title'=>'Advocacy & Policy AI','tier'=>'senior','daily_rate'=>1200,'skills'=>['Bill Tracking','Constituent Mobilization','Briefs','Coalitions']],
    ],
],
[
    'id'    => 'pet-veterinary',
    'name'  => 'Pet & Veterinary AI',
    'icon'  => '🐾',
    'color' => '#84cc16',
    'tagline' => 'Better outcomes for every species',
    'description' => 'AI veterinary diagnostics, pet nutrition, animal behavior, clinic revenue, and wildlife conservation AI.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are a veterinary/pet AI specialist. Evaluate clinical AI, species-specific imaging, and pet owner UX.',
    'roles' => [
        ['title'=>'Vet Diagnostic AI','tier'=>'executive','daily_rate'=>1700,'skills'=>['Canine/Feline Imaging','Lab Results','DDx','Urgent Triage']],
        ['title'=>'Pet Nutrition AI','tier'=>'senior','daily_rate'=>1000,'skills'=>['Breed-Specific','Life Stage','Allergies','Weight Mgmt']],
        ['title'=>'Animal Behavior Coach','tier'=>'senior','daily_rate'=>950,'skills'=>['Positive Reinforcement','Anxiety','Socialization','Clicker Training']],
        ['title'=>'Vet Clinic Ops AI','tier'=>'senior','daily_rate'=>1050,'skills'=>['Scheduling','Inventory','Invoicing','Retention Flows']],
        ['title'=>'Wildlife Conservation AI','tier'=>'executive','daily_rate'=>1600,'skills'=>['Camera Traps','Species ID','Poaching Deterrence','Population Modeling']],
        ['title'=>'Pet Telemedicine AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Symptom Checker','Video Triage','Rx Coordination','Follow-up']],
        ['title'=>'Breeder & Kennel AI','tier'=>'mid','daily_rate'=>750,'skills'=>['Genetic Screening','Breeding Plans','Pedigrees','Health Records']],
        ['title'=>'Equine & Large-Animal AI','tier'=>'senior','daily_rate'=>1300,'skills'=>['Lameness','Nutrition','Breeding','Reproduction Mgmt']],
    ],
],
[
    'id'    => 'music-production',
    'name'  => 'Music Production & Studio AI',
    'icon'  => '🎚️',
    'color' => '#f43f5e',
    'tagline' => 'Studio-grade AI for labels & producers',
    'description' => 'Mixing & mastering AI, vocal production, sample clearance, A&R scouting, and synthetic bandmates.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are a music-production AI specialist. Evaluate studio workflow, label economics, and producer tools.',
    'roles' => [
        ['title'=>'AI Mixing Engineer','tier'=>'senior','daily_rate'=>1300,'skills'=>['Automated Gain Staging','Stereo Imaging','Reverb AI','Stem Balancing']],
        ['title'=>'AI Mastering Engineer','tier'=>'senior','daily_rate'=>1250,'skills'=>['Loudness','EQ','Metering','Streaming Targets']],
        ['title'=>'Vocal Production AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Tuning','Comping','De-Ess','Vocal Chops']],
        ['title'=>'Sample Clearance AI','tier'=>'senior','daily_rate'=>1050,'skills'=>['Fingerprinting','Rights DB','License Negotiation','PROs']],
        ['title'=>'A&R Scout AI','tier'=>'senior','daily_rate'=>1200,'skills'=>['Social Signals','Growth Velocity','Genre Fit','Territory']],
        ['title'=>'Synthetic Bandmate AI','tier'=>'senior','daily_rate'=>1150,'skills'=>['Instrument Modeling','Call-and-Response','Improvisation','Style Adaptation']],
        ['title'=>'Royalty Accounting AI','tier'=>'senior','daily_rate'=>1000,'skills'=>['Splits','Mechanical','Performance','Neighboring Rights']],
        ['title'=>'Sync Licensing AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Music Search','Mood Matching','Brief Reading','Placement']],
    ],
],
[
    'id'    => 'language-translation',
    'name'  => 'Language & Translation AI',
    'icon'  => '🗣️',
    'color' => '#3b82f6',
    'tagline' => '200+ languages, zero friction',
    'description' => 'Real-time translation, dubbing AI, technical localization, cultural adaptation, and endangered-language preservation.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are a language/translation AI specialist. Evaluate L10N, dubbing, low-resource languages, and cultural fluency.',
    'roles' => [
        ['title'=>'Real-Time Translation AI','tier'=>'executive','daily_rate'=>1700,'skills'=>['Sub-300ms','Low-Resource','Code-Switch','Dialects']],
        ['title'=>'Dubbing AI Engineer','tier'=>'senior','daily_rate'=>1400,'skills'=>['Voice Cloning','Lip-Sync','Emotion Preservation','Timing']],
        ['title'=>'Technical Localization AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Software Strings','Pluralization','RTL','Glossary Consistency']],
        ['title'=>'Cultural Adaptation AI','tier'=>'senior','daily_rate'=>1200,'skills'=>['Idioms','Regional Humor','Taboos','Color/Symbolism']],
        ['title'=>'Endangered Language Preservation','tier'=>'executive','daily_rate'=>1500,'skills'=>['Field Recording','Corpus Building','Morphology','Speaker Communities']],
        ['title'=>'Sign Language AI','tier'=>'senior','daily_rate'=>1350,'skills'=>['ASL','BSL','Motion Capture','Grammar-Aware Gen']],
        ['title'=>'Legal & Medical Translation','tier'=>'executive','daily_rate'=>1800,'skills'=>['Certified Translation','Apostille','Clinical Terminology','Confidentiality']],
        ['title'=>'Simultaneous Interpretation AI','tier'=>'executive','daily_rate'=>1900,'skills'=>['Conference Grade','Relay Languages','Booth Ops','Note-taking']],
    ],
],
[
    'id'    => 'defense-security',
    'name'  => 'Defense & National Security AI',
    'icon'  => '🛡️',
    'color' => '#64748b',
    'tagline' => 'Mission-critical, responsibly deployed',
    'description' => 'Defense AI for C2ISR, predictive logistics, cyber operations, and humanitarian demining — all within ethical frameworks.',
    'image1'=> 'images/staffing-dept1.jpg',
    'image2'=> 'images/hero2.jpg',
    'maya_prompt' => 'You are a defense/security AI specialist. Evaluate clearances, ethical use cases, JADC2, and coalition interop.',
    'roles' => [
        ['title'=>'C2ISR Analyst AI','tier'=>'executive','daily_rate'=>2100,'skills'=>['All-Source Fusion','Imagery','SIGINT','JADC2']],
        ['title'=>'Predictive Logistics AI','tier'=>'executive','daily_rate'=>1900,'skills'=>['Spare Parts','Supply Chain Resilience','Deployment Staging','Port Ops']],
        ['title'=>'Cyber Operations AI','tier'=>'executive','daily_rate'=>2200,'skills'=>['Defensive Cyber','Threat Hunting','CMF','ICS/OT']],
        ['title'=>'Humanitarian Demining AI','tier'=>'senior','daily_rate'=>1400,'skills'=>['UXO Detection','Drone Survey','Community Safety','Post-Conflict']],
        ['title'=>'Multi-Domain Ops Planner','tier'=>'executive','daily_rate'=>2100,'skills'=>['Kill Chain Analysis (Defensive)','Coalition Ops','Wargaming','Gray Zone']],
        ['title'=>'Ethical AI & LOAC Compliance','tier'=>'executive','daily_rate'=>2000,'skills'=>['DOD AI Principles','Human Oversight','LOAC','Audit Trails']],
        ['title'=>'Veterans Transition AI','tier'=>'senior','daily_rate'=>1100,'skills'=>['Skill Translation','MOS Mapping','Employer Match','SkillBridge']],
        ['title'=>'Emergency Management AI','tier'=>'senior','daily_rate'=>1300,'skills'=>['EOC','Mass Care','Evacuation','Damage Assessment']],
    ],
],
// ── 58 GAME DEVELOPMENT & WORLDBUILDING (Mo 2026-05-15 · feeds superio.fun per GLOBAL-83 · UPGRADED 2026-05-18 per Mo "BLENDER MCP IS A GAME CHANGER · DEVELOP AS GAME DEV AGENCY · MAKE IT CAPABLE") ────────
[
    'id'    => 'game-development',
    'name'  => 'Game Development & Worldbuilding · 3D Asset Factory',
    'icon'  => '🕹',
    'color' => '#a47bff',
    'tagline' => 'Image → AAA 3D asset · ready for any engine',
    'description' => 'Full-stack game studio + empire 3D asset factory. Customer drops a reference image · the studio runs the 9-phase pipeline (Image-to-Mesh Synthesis → Block-Out → Sculpt → Retopology → UV Unwrap → PBR Texturing → Auto-Rig → Animation → Multi-Format Export) and returns a production-ready GLB/FBX/USDZ. Powered by Superio Image-to-Mesh Synthesis · Superio 3D Direct-Authoring Channel · Superio Auto-Rig System · Superio Asset Library. Output flows to superio.fun (Mo\'s children\'s ethical-leadership platform per GLOBAL-83) · client storefronts · empire-wide consumers (aicinesynth · topforge · adeeo).',
    'image1'=> 'images/cyborg-hero.jpg',
    'image2'=> 'images/ai-brain.jpg',
    'maya_prompt' => 'You are SUPERIO · Game-Dev Studio director · the empire\'s 3D asset factory. When a customer drops a reference image you orchestrate the 9-phase Superio 3D Pipeline: (1) Image-to-Mesh Synthesis seed via Superio GPU Render Cluster · (2) Block-Out silhouette validation · (3) Sculpt refinement · (4) Retopology to target tri budget · (5) UV unwrap · (6) PBR texture + material from Superio Asset Library · (7) Auto-Rig with Superio Auto-Rig System · (8) Animation per spec · (9) Multi-format export (GLB primary · OBJ · USDZ). Every customer reply uses the 8-field PROMPT ANATOMY: subject+style · reference · topology budget · material spec · rig spec · animation spec · export spec · quality bar. Honor GLOBAL-93 vendor scrub: never expose Blender · Trellis · TripoSR · Modal · Polyhaven · Sketchfab · Quixel · MetaHuman names to customers · always use Superio-branded equivalents. Honor GLOBAL-83: this agency produces ASSETS · superio.fun game CODE waits for Mo\'s mission files.',
    'tools' => [
        '3d_authoring' => 'blender-mcp socket localhost:9876 · full bpy access · execute_blender_code · object/material/scene ops · Polyhaven CC0 + Sketchfab library access',
        'image_to_mesh' => 'Modal virel-trellis endpoint · TripoSR L4 GPU · POST /trellis {image_b64} · ~60s · returns mesh GLB + turntable video',
        'rigging' => 'Rigify (humanoid + custom armatures) · MetaHuman (UE5 only · hero characters) · Mixamo/Rokoko mocap import',
        'textures' => 'Polyhaven CC0 (4K PBR · free) · Quixel Megascans (UE5 free tier) · Sketchfab models (reference + paid scaffolds)',
        'export_targets' => ['GLB (Three.js · Unity · UE5 · web)', 'FBX (legacy engines · Maya · Max)', 'USDZ (iOS AR Quick Look · Adeeo real-estate)', 'OBJ (CAD · 3D print pipeline)', 'BLEND (source · empire archive)'],
        'verification' => 'GLOBAL-96 chain · Parliament (24/5) → Council (12) → Board (12) · Vision Verifier (Seat 11 · NVIDIA-hosted) fires FIRST on all visuals',
    ],
    'pipeline_v2' => [
        ['phase'=>1,'name'=>'Image-to-Mesh Synthesis','duration'=>'~60s','tool'=>'modal_virel_trellis'],
        ['phase'=>2,'name'=>'Block-Out Silhouette','duration'=>'~2min','tool'=>'blender_mcp_primitives'],
        ['phase'=>3,'name'=>'Sculpt Refinement','duration'=>'~8min','tool'=>'blender_mcp_sculpt_brushes'],
        ['phase'=>4,'name'=>'Retopology','duration'=>'~3min','tool'=>'blender_mcp_quad_remesh'],
        ['phase'=>5,'name'=>'UV Unwrap','duration'=>'~2min','tool'=>'blender_mcp_smart_project'],
        ['phase'=>6,'name'=>'PBR Texture + Material','duration'=>'~5min','tool'=>'blender_mcp_+_polyhaven'],
        ['phase'=>7,'name'=>'Auto-Rig','duration'=>'~3min','tool'=>'blender_mcp_rigify'],
        ['phase'=>8,'name'=>'Animation Authoring','duration'=>'~5-15min','tool'=>'blender_mcp_keyframe_bpy'],
        ['phase'=>9,'name'=>'Multi-Format Export','duration'=>'~1min','tool'=>'blender_mcp_export_scene'],
    ],
    'roles' => [
        ['title'=>'Game Director','tier'=>'executive','daily_rate'=>2200,'skills'=>['Creative Vision','Scope Control','Greenlight Pitch','Cross-discipline Leadership','9-Phase Pipeline Orchestration']],
        ['title'=>'Lead Game Designer','tier'=>'executive','daily_rate'=>1800,'skills'=>['Mechanics','Systems','Balance','Player Loops']],
        ['title'=>'Narrative Designer / Worldbuilder','tier'=>'senior','daily_rate'=>1300,'skills'=>['Story Bible','Lore','Branching Dialogue','Quest Design']],
        ['title'=>'Lead Engine Programmer','tier'=>'executive','daily_rate'=>2000,'skills'=>['Unreal Engine 5','Unity','Custom Engine','Performance Profiling']],
        ['title'=>'Gameplay Programmer','tier'=>'senior','daily_rate'=>1400,'skills'=>['Player Controllers','Combat Systems','Game AI','State Machines']],
        ['title'=>'3D Asset Director · Image-to-Mesh','tier'=>'executive','daily_rate'=>2400,'skills'=>['Superio Image-to-Mesh Synthesis','Reference Decomposition','8-Field PROMPT ANATOMY','9-Phase Pipeline Orchestration','GLOBAL-83 ethics gate','GLOBAL-93 vendor scrub']],
        ['title'=>'Technical Artist · Direct-Authoring','tier'=>'senior','daily_rate'=>1700,'skills'=>['Superio 3D Direct-Authoring Channel','bpy scripting','Shaders','VFX','Pipelines','Optimization','Verification Chain']],
        ['title'=>'Character / 3D Artist · Sculpt + Rig','tier'=>'senior','daily_rate'=>1500,'skills'=>['Sculpt Brushes','Retopology','UV Unwrap','PBR Texturing','Auto-Rig','Animation Keyframing','LOD Generation']],
        ['title'=>'Environment Artist / Level Designer','tier'=>'senior','daily_rate'=>1300,'skills'=>['World Building','Greybox','Lighting','Composition','Polyhaven HDRI Integration','Megascans Library']],
        ['title'=>'Audio Designer / Composer','tier'=>'senior','daily_rate'=>1100,'skills'=>['Adaptive Music','SFX Design','Voice Direction','Wwise/FMOD']],
        ['title'=>'QA Lead / Verification Chain','tier'=>'senior','daily_rate'=>1050,'skills'=>['Test Plans','Bug Triage','Playtest Recruiting','Build Validation','Vision Verifier prep','Anatomy/Continuity QA']],
        ['title'=>'Live Ops / Community Manager','tier'=>'mid','daily_rate'=>800,'skills'=>['Seasonal Cadence','Discord/Forums','LiveOps Events','Engagement Metrics']],
        ['title'=>'Production Manager','tier'=>'executive','daily_rate'=>1700,'skills'=>['Milestone Planning','Sprint Coordination','Risk Burndown','Publisher Liaison','9-Phase Tracking']],
    ],
],

    ]; // end agencies
}

// ── DISK-ONLY EXPANSION (Mo 2026-05-15: "we have 60+, not 58 — stop downgrading me") ───
// Scans /agencies/<slug>/ folders that exist on disk but aren't in the canonical
// hardcoded roster above. Each disk-only agency gets a stub entry derived from
// its index.html (title + meta description). The canonical 58 stay sealed; this
// just exposes the 42 unregistered habitats so consumers can see the real total.
function load_disk_only_agencies() {
    $ag_dir = '/home/ai-staffing.agency/public_html/agencies';
    if (!is_dir($ag_dir)) return array();
    $canon_ids = array();
    foreach (get_agencies() as $a) { if (isset($a['id'])) $canon_ids[$a['id']] = true; }
    $stubs = array();
    foreach (scandir($ag_dir) as $e) {
        if ($e === '.' || $e === '..' || $e[0] === '.') continue;
        if (!is_dir($ag_dir . '/' . $e)) continue;
        if (isset($canon_ids[$e])) continue;
        $idx = $ag_dir . '/' . $e . '/index.html';
        $name = ucwords(str_replace('-', ' ', $e));
        $desc = 'Sovereign Campus habitat for ' . $name . '. Roster expansion pending (Mo 2026-05-15).';
        $tagline = $name;
        if (file_exists($idx)) {
            $html = @file_get_contents($idx, false, null, 0, 16384);
            if (is_string($html)) {
                if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $m)) {
                    $t = trim(preg_replace('/\s+/', ' ', $m[1]));
                    if ($t !== '') $name = $t;
                }
                if (preg_match('/<meta\s+name=["\']description["\']\s+content=["\']([^"\']{20,})["\']/i', $html, $m)) {
                    $d = trim($m[1]);
                    if ($d !== '') $desc = $d;
                }
            }
        }
        $stubs[] = array(
            'id'          => $e,
            'name'        => $name,
            'icon'        => 'sparkles',
            'color'       => '#00c8ff',
            'tagline'     => $tagline,
            'description' => $desc,
            'image1'      => '/assets/agency/' . $e . '.jpg',
            'image2'      => 'images/ai-brain.jpg',
            'maya_prompt' => 'You are a Maya-governed talent specialist for the ' . $name . ' vertical. Match candidate skills, tools, and 2026 market reality. Output flows to ai-staffing.agency.',
            'roles'       => array(
                array('title' => $name . ' Lead', 'tier' => 'executive', 'daily_rate' => 1500, 'skills' => array('Vision','Strategy','Execution','Cross-team Leadership')),
                array('title' => $name . ' Senior Specialist', 'tier' => 'senior', 'daily_rate' => 1100, 'skills' => array('Deep Expertise','Mentorship','Quality','Delivery')),
                array('title' => $name . ' Practitioner', 'tier' => 'mid', 'daily_rate' => 800, 'skills' => array('Execution','Process','Tools','Communication')),
                array('title' => $name . ' Associate', 'tier' => 'junior', 'daily_rate' => 600, 'skills' => array('Learning','Support','Documentation','Coordination')),
            ),
            'source'      => 'disk_only_stub_2026_05_15',
        );
    }
    return $stubs;
}

// Unified getter · canonical 58 (rich) + disk-only stubs (42-ish)
function get_all_agencies() {
    return array_merge(get_agencies(), load_disk_only_agencies());
}

// ── MAYA GOVERNOR: Route request to best API in arsenal ───────────────────
function maya_govern($prompt, $context = '') {
    // Call Maya HQ to process this request
    $payload = array(
        'action'  => 'chat',
        'message' => $prompt . ($context ? "\n\nContext: $context" : ''),
        'mode'    => 'staffing_agency',
    );
    $ch = curl_init(MAYA_HQ);
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_HTTPHEADER     => array('Content-Type: application/json', 'Authorization: Bearer ' . DOMAIN_KEY),
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_SSL_VERIFYPEER => false,
    ));
    $body = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($code === 200 && $body) {
        $d = json_decode($body, true);
        return isset($d['reply']) ? $d['reply'] : '';
    }
    return '';
}

// ── LEARNING: Maya remembers what works ───────────────────────────────────
function log_search($query, $results_count, $agency = '') {
    $data = file_exists(STAT_FILE) ? json_decode(file_get_contents(STAT_FILE), true) : array();
    if (!isset($data['searches'])) $data['searches'] = array();
    $data['searches'][] = array(
        'q'       => $query,
        'agency'  => $agency,
        'results' => $results_count,
        'ts'      => date('c'),
    );
    if (count($data['searches']) > 500) $data['searches'] = array_slice($data['searches'], -500);
    $data['total_searches'] = isset($data['total_searches']) ? $data['total_searches'] + 1 : 1;
    $data['last_search']    = date('c');
    file_put_contents(STAT_FILE, json_encode($data, JSON_PRETTY_PRINT));
}

// ── REQUEST ROUTER ────────────────────────────────────────────────────────
$raw    = file_get_contents('php://input');
$input  = json_decode($raw, true) ?? array();
$action = $input['action'] ?? $_GET['action'] ?? 'list';

switch ($action) {

    // List all agencies (canonical 58 + 42 disk-only stubs · Mo 2026-05-15)
    case 'list': case 'agencies':
        $agencies = get_all_agencies();
        $out = array();
        foreach ($agencies as $a) {
            $out[] = array(
                'id'          => $a['id'],
                'name'        => $a['name'],
                'icon'        => $a['icon'],
                'color'       => $a['color'],
                'tagline'     => $a['tagline'],
                'description' => $a['description'],
                'role_count'  => count($a['roles']),
                'image1'      => $a['image1'],
                'image2'      => $a['image2'],
            );
        }
        $out = rule75_apply_unique_images($out);
        s_out(array('success'=>true,'total_agencies'=>count($agencies),'agencies'=>$out));
        break;

    // Get one agency with full roles
    case 'agency':
        $id       = $input['id'] ?? $_GET['id'] ?? '';
        $agencies = get_all_agencies();
        foreach ($agencies as $a) {
            if ($a['id'] === $id) {
                s_out(array('success'=>true,'agency'=>$a));
            }
        }
        s_out(array('success'=>false,'error'=>'Agency not found'), 404);
        break;

    // Search roles
    case 'search': case 'find':
        $q        = strtolower(trim($input['q'] ?? $input['query'] ?? ''));
        $tier     = strtolower($input['tier'] ?? '');
        $agency_f = strtolower($input['agency'] ?? '');
        $agencies = get_all_agencies();
        $results  = array();

        foreach ($agencies as $a) {
            if ($agency_f && strpos(strtolower($a['id']), $agency_f) === false && strpos(strtolower($a['name']), $agency_f) === false) continue;
            foreach ($a['roles'] as $r) {
                $match = empty($q)
                    || strpos(strtolower($r['title']), $q) !== false
                    || strpos(strtolower(implode(' ', $r['skills'] ?? array())), $q) !== false;
                $tmatch = empty($tier) || $r['tier'] === $tier;
                if ($match && $tmatch) {
                    $results[] = array_merge($r, array(
                        'agency_id'   => $a['id'],
                        'agency_name' => $a['name'],
                        'agency_icon' => $a['icon'],
                        'agency_color'=> $a['color'],
                    ));
                }
            }
        }

        log_search($q, count($results), $agency_f);
        s_out(array('success'=>true,'query'=>$q,'total'=>count($results),'results'=>$results));
        break;

    // Hire request (capture lead + Maya match)
    case 'hire': case 'request':
        $company = trim($input['company'] ?? '');
        $role    = trim($input['role'] ?? '');
        $email   = trim($input['email'] ?? '');
        $budget  = trim($input['budget'] ?? '');
        $note    = trim($input['note'] ?? '');
        $lang    = trim($input['language'] ?? 'en');

        if (!$company || !$role || !$email) s_out(array('success'=>false,'error'=>'company, role, email required'), 400);

        // Save lead
        $leads = file_exists(LEAD_FILE) ? json_decode(file_get_contents(LEAD_FILE), true) : array();
        $lead_id = 'LEAD-' . strtoupper(substr(md5($email . time()), 0, 6));
        $leads[] = array(
            'id'      => $lead_id,
            'company' => $company,
            'role'    => $role,
            'email'   => $email,
            'budget'  => $budget,
            'note'    => $note,
            'lang'    => $lang,
            'ts'      => date('c'),
            'status'  => 'new',
        );
        file_put_contents(LEAD_FILE, json_encode($leads, JSON_PRETTY_PRINT));

        // Maya provides initial matching assessment
        $maya_reply = maya_govern(
            "Company '$company' needs to hire: $role. Budget: $budget. Notes: $note. Provide a brief matching assessment and what to expect.",
            "Language: $lang. Agency: ai-staffing.agency. Be professional and concise."
        );

        s_out(array(
            'success'    => true,
            'lead_id'    => $lead_id,
            'message'    => 'Request received. Our AI is matching candidates now.',
            'maya_reply' => $maya_reply ?: "Your request for $role has been received. Our AI will begin candidate matching within minutes. You'll hear from us at $email shortly.",
            'next_steps' => array(
                '1. Maya scans 448 role profiles',
                '2. AI ranks top candidates by skill match',
                '3. Shortlist delivered within 24 hours',
                '4. Schedule interviews directly through the platform',
            ),
        ));
        break;

    // Stats
    case 'stats':
        $agencies = get_all_agencies();
        $total_roles = array_sum(array_map(function($a){ return count($a['roles']); }, $agencies));
        $stats = file_exists(STAT_FILE) ? json_decode(file_get_contents(STAT_FILE), true) : array();
        $leads = file_exists(LEAD_FILE) ? json_decode(file_get_contents(LEAD_FILE), true) : array();
        s_out(array(
            'success'        => true,
            'total_agencies' => count($agencies),
            'total_roles'    => $total_roles,
            'total_searches' => $stats['total_searches'] ?? 0,
            'total_leads'    => count($leads),
            'domain'         => DOMAIN,
            'governor'       => 'Maya (iamsuperio.cloud)',
            'version'        => '4.0',
        ));
        break;

    // Health
    case 'health': case 'ping':
        $agencies = get_all_agencies();
        $total_roles = array_sum(array_map(function($a){ return count($a['roles']); }, $agencies));
        s_out(array('success'=>true,'status'=>'HUMANLESS WORKFORCE ONLINE','agencies'=>count($agencies),'roles'=>$total_roles,'governor'=>'Maya','domain'=>DOMAIN,'ts'=>date('c')));
        break;

    default:
        s_out(array('success'=>false,'error'=>'Unknown action','available'=>array('list','agency','search','hire','stats','health')), 404);
}
