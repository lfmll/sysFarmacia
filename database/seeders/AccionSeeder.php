<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clase;

class AccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clases = [
            ['nombre'=>'(A01) Estomalógicos','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A02) Antiácidos, antiflatulentos y antiulcerosos','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A03) Fármacos para los desórdenes gastrointestinales','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A04) Antieméticos y antinauseosos','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A05) Colagogos y protectores hepáticos','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A06) Laxantes','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A07) Antidiarreicos, susbs. de electrolitos orales, antiiflamatorios y antiinfecciosos intestinales','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A08) Preparados antiobesidad, excluyendo los dietéticos','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A09) Digestivos incluyendo enzimas digestivas','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A10) Productos utilizados para la diabetes','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A11) Vitaminas','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A12) Suplementos minerales','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A13) Tónicos','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A14) Anabólicos, sistémicos','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A15) Estimulantes del apetito','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(A16) Otros productos para el tracto alimentario y el metabolismo','clase'=>'(A) Tracto alimentario y metabolismo'],
            ['nombre'=>'(B01) Agentes antitrombóticos','clase'=>'(B) Sangre y aparato hematopoyético'],
            ['nombre'=>'(B02) Antihemorrágicos','clase'=>'(B) Sangre y aparato hematopoyético'],
            ['nombre'=>'(B03) Antianémicos','clase'=>'(B) Sangre y aparato hematopoyético'],
            ['nombre'=>'(B05) Sustitutos del plasma y soluciones para perfusión','clase'=>'(B) Sangre y aparato hematopoyético'],
            ['nombre'=>'(B06) Otros agentes hematológicos','clase'=>'(B) Sangre y aparato hematopoyético'],
            ['nombre'=>'(C01) Terapia cardiaca','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C02) Antihipertensivos','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C03) Diuréticos','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C04) Vasodilatadores periféricos','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C05) Vasoprotectores','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C07) Betabloqueantes','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C08) Antagonista del calcio','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C09) Agentes activos en el sistema en angiotensinas y renina','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C10) Preparaciones antiateroma/Reguladoras de lípidos','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(C11) Productos para la terapia múltiple cardiovascular','clase'=>'(C) Sistema cardiovascular'],
            ['nombre'=>'(D01) Antimicóticos dermatológicos','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D02) Emolientes protectores','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D03) Agentes curativos, heridas','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D04) Antipruriginosos, incluyendo antihistamínicos tópicos, anestésicos, etc','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D05) Productos no esteorideos para transtornos inflamatorios de la piel','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D06) Antivirales y antibacteriales tópicos','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D07) Corticosteroides tópicos','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D08) Antisépticos tópicos','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D09) Apósitos medicamentosos','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D10) Preparados contra acné','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(D11) Otros preparados dermatológicos','clase'=>'(D) Dermatológicos'],
            ['nombre'=>'(G01) Antiinfecciosos y Antisépticos ginecológicos','clase'=>'(G) Sistema genitourinario y hormonas sexuales'],
            ['nombre'=>'(G02) Otros productos ginecológicos','clase'=>'(G) Sistema genitourinario y hormonas sexuales'],
            ['nombre'=>'(G03) Hormonas sexuales y productos con efectos deseados similares, solo acción sistémica','clase'=>'(G) Sistema genitourinario y hormonas sexuales'],
            ['nombre'=>'(G04) Urológicos','clase'=>'(G) Sistema genitourinario y hormonas sexuales'],
            ['nombre'=>'(H01) Hormonas hipofisarias e hipotalámicas y sus análogos','clase'=>'(H) Hormonales sistémicos'],
            ['nombre'=>'(H02) Corticosteroides para uso sistémico','clase'=>'(H) Hormonales sistémicos'],
            ['nombre'=>'(H03) Terapia tiroidea','clase'=>'(H) Hormonales sistémicos'],
            ['nombre'=>'(H04) Hormonas pancreáticas','clase'=>'(H) Hormonales sistémicos'],
            ['nombre'=>'(H05) Homeostasis del calcio','clase'=>'(H) Hormonales sistémicos'],
            ['nombre'=>'(J01) Antibacterianos sistémicos','clase'=>'(J) Antiinfecciosos'],
            ['nombre'=>'(J02) Agentes para infecciones fúngicas sistémicas','clase'=>'(J) Antiinfecciosos'],            
            ['nombre'=>'(J03) Sulfonamidas sistémicas','clase'=>'(J) Antiinfecciosos'],
            ['nombre'=>'(J04) Antimicobacteriales','clase'=>'(J) Antiinfecciosos'],
            ['nombre'=>'(J05) Antivirales sistémicos','clase'=>'(J) Antiinfecciosos'],
            ['nombre'=>'(J06) Sueros gammaglobulinas','clase'=>'(J) Antiinfecciosos'],
            ['nombre'=>'(J07) Vacunas','clase'=>'(J) Antiinfecciosos'],            
            ['nombre'=>'(J08) Otros antiinfecciosos','clase'=>'(J) Antiinfecciosos'],
            ['nombre'=>'(K01) Soluciones intravenosas','clase'=>'(K) Soluciones hospitalarias'],
            ['nombre'=>'(K02) Expansores del plasma','clase'=>'(K) Soluciones hospitalarias'],
            ['nombre'=>'(K03) Sangre y sustitutos del plasma','clase'=>'(K) Soluciones hospitalarias'],
            ['nombre'=>'(K04) Soluciones inyectables/Infusiones aditivas (<100 ml)','clase'=>'(K) Soluciones hospitalarias'],
            ['nombre'=>'(K06) Soluciones diálisis','clase'=>'(K) Soluciones hospitalarias'],
            ['nombre'=>'(L01) Antineoplásicos','clase'=>'(L) Antineoplásicos e inmunomoduladores'],
            ['nombre'=>'(L02) Terapia hormonal citostástica','clase'=>'(L) Antineoplásicos e inmunomoduladores'],
            ['nombre'=>'(L03) Agentes inmunoestimulantes','clase'=>'(L) Antineoplásicos e inmunomoduladores'],
            ['nombre'=>'(L04) Agentes inmunosupresores','clase'=>'(L) Antineoplásicos e inmunomoduladores'],
            ['nombre'=>'(M01) Preparados antiinflamatorios y antirreumáticos','clase'=>'(M) Sistema músculo esquelético'],
            ['nombre'=>'(M02) Antirreumáticos tópicos','clase'=>'(M) Sistema músculo esquelético'],
            ['nombre'=>'(M03) Relajantes musculares','clase'=>'(M) Sistema músculo esquelético'],
            ['nombre'=>'(M04) Antigotosos','clase'=>'(M) Sistema músculo esquelético'],
            ['nombre'=>'(M05) Drogas para el tratamiento de enfermedades óseas','clase'=>'(M) Sistema músculo esquelético'],
            ['nombre'=>'(M09) Otras drogas para desórdenes del sistema musculoesqueléticos','clase'=>'(M) Sistema músculo esquelético'],
            ['nombre'=>'(N01) Anestésicos','clase'=>'(N) Sistema nervioso central'],
            ['nombre'=>'(N02) Analgésicos','clase'=>'(N) Sistema nervioso central'],
            ['nombre'=>'(N03) Antiepilépticos','clase'=>'(N) Sistema nervioso central'],
            ['nombre'=>'(N04) Antiparkinsonianos','clase'=>'(N) Sistema nervioso central'],
            ['nombre'=>'(N05) Psicolépticos','clase'=>'(N) Sistema nervioso central'],
            ['nombre'=>'(N06) Psicoanlépticos excluyendo preparados antiobesidad','clase'=>'(N) Sistema nervioso central'],
            ['nombre'=>'(N07) Otros productos para SNC','clase'=>'(N) Sistema nervioso central'],
            ['nombre'=>'(P01) Antiprotozoarios','clase'=>'(P) Parasitología'],
            ['nombre'=>'(P02) Antihelmínticos','clase'=>'(P) Parasitología'],
            ['nombre'=>'(P03) Ectoparasiticidas, incluyendo escabicidas, insecticidas y repelentes','clase'=>'(P) Parasitología'],
            ['nombre'=>'(R01) Preparados nasales','clase'=>'(R) Sistema respiratorio'],
            ['nombre'=>'(R02) Preparados para la garganta','clase'=>'(R) Sistema respiratorio'],
            ['nombre'=>'(R03) Productos COPD y antiasma','clase'=>'(R) Sistema respiratorio'],
            ['nombre'=>'(R04) Revulsivos y otros inhalantes','clase'=>'(R) Sistema respiratorio'],
            ['nombre'=>'(R05) Preparados para el resfriado y la tos','clase'=>'(R) Sistema respiratorio'],
            ['nombre'=>'(R06) Antihistamínicos sistémicos','clase'=>'(R) Sistema respiratorio'],
            ['nombre'=>'(R07) Otros preparados para el sistema respiratorio','clase'=>'(R) Sistema respiratorio'],
            ['nombre'=>'(S01) Oftalmológicos','clase'=>'(S) Órganos de los sentidos'],
            ['nombre'=>'(S02) Otológicos','clase'=>'(S) Órganos de los sentidos'],
            ['nombre'=>'(V03) Demás productos terapéuticos','clase'=>'(V) Varios'],
            ['nombre'=>'(V06) Agentes para dietas','clase'=>'(V) Varios'],
            ['nombre'=>'(V07) Productos no terapéuticos','clase'=>'(V) Varios'],
            ['nombre'=>'(V08) Medios de contraste','clase'=>'(V) Varios']
        ];

        foreach ($clases as $clase) {
            Clase::create($clase);
        }
    }
}
