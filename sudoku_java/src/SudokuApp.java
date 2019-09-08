/*
 * SudokuApp.java
 */
package sudoku;

import java.util.Random;
import javax.swing.JFrame;

/**
 *
 * @author Kuba
 */
public class SudokuApp extends JFrame {

    DrawingPanel dp = new DrawingPanel();

    public void prednastavPole() {
        for (int i = 0; i < 81; i++) {
            dp.mnozina[i] = 9;
            for (int j = 1; j <= 9; j++) {
                dp.pole[i][j] = 1;
            }
        }
    }

    public int generujPole(int pozice) {
        Random generator = new Random();
        int n[] = new int[9];
        int counter = 0;
        for (int i = 1; i <= 9; i++) {
            if (dp.pole[pozice][i] == 1) {
                n[counter] = i;
                counter++;
            }
        }
        if (counter == 0) {
            return 0;
        }
        return n[generator.nextInt(counter)];
    }

    public void vypisVysledek() {
        for (int i = 0; i < 9; i++) {
            for (int j = 0; j < 9; j++) {
                if (dp.hraci_deska[i][j] == 0) {
                    System.out.print(".");
                } else {
                    System.out.print(dp.hraci_deska[i][j]);
                }
                if (j == 2 || j == 5) {
                    System.out.print("|");
                }
            }
            System.out.println();
            if (i == 2 || i == 5) {
                for (int l = 0; l < 3; l++) {
                    for (int m = 0; m < 3; m++) {
                        System.out.print("-");
                    }
                    if (l != 2) {
                        System.out.print("+");
                    }
                }
                System.out.println();
            }
        }
    }

    public boolean nastavHorizontalu(int radek, int cislo) {
        boolean status = true;
        for (int i = 9 * radek; i < 9 * (radek + 1); i++) {
            if (dp.pole[i][cislo] == 1) {
                dp.pole[i][cislo] = 0;
                dp.mnozina[i]--;
            } else if (dp.hraci_deska[i / 9][i % 9] == cislo) {
                status = false;
            }
        }
        return status;
    }

    public boolean nastavVertikalu(int sloupec, int vynechat, int cislo) {
        boolean status = true;
        for (int i = sloupec; i < 81; i += 9) {
            if (i == vynechat) {
                continue;
            }
            if (dp.pole[i][cislo] == 1) {
                dp.pole[i][cislo] = 0;
                dp.mnozina[i]--;
            } else if (dp.hraci_deska[i / 9][i % 9] == cislo) {
                status = false;
            }
        }
        return status;
    }

    public boolean nastavCtverec(int radek, int sloupec, int cislo) {
        boolean status = true;
        int pocatek;
        if (sloupec < 3) {
            pocatek = 0;
        } else if (sloupec > 5) {
            pocatek = 6;
        } else {
            pocatek = 3;
        }

        if (radek >= 3 && radek <= 5) {
            pocatek += 27;
        }
        if (radek >= 6) {
            pocatek += 54;
        }

        //hledáme na nastavení 2x2 čtverec sestavený z polí, které se nedotýkají našeho bodu horizontálně ani vertikálně
        //a leží v poli 3x3

        for (int k = 0, i= 0; i <= 18; i+=9, k++) {            
            for (int j = 0; j < 3; j++) {
                if ((int) (pocatek / 9) + k != radek && (pocatek + j) % 9 != sloupec) {
                    if (dp.pole[pocatek + i + j][cislo] == 1) {
                        dp.pole[pocatek + i + j][cislo] = 0;
                        dp.mnozina[pocatek + i + j]--;
                    } else if (dp.hraci_deska[(pocatek + i + j) / 9][(pocatek + i + j) % 9] == cislo) {
                        status = false;
                    }
                }
            }
        }
        return status;
    }

    public void quickSort(int start, int end) {
        int i = start;
        int k = end;
        int swap[] = new int[2];

        if (end - start >= 1) {
            int pivot = dp.mnozina_sort[start][1];

            while (k > i) {
                while (dp.mnozina_sort[i][1] <= pivot && i <= end && k > i) {
                    i++;
                }
                while (dp.mnozina_sort[k][1] > pivot && k >= start && k >= i) {
                    k--;
                }
                if (k > i) {
                    swap[0] = dp.mnozina_sort[i][0];
                    swap[1] = dp.mnozina_sort[i][1];
                    dp.mnozina_sort[i][0] = dp.mnozina_sort[k][0];
                    dp.mnozina_sort[i][1] = dp.mnozina_sort[k][1];
                    dp.mnozina_sort[k][0] = swap[0];
                    dp.mnozina_sort[k][1] = swap[1];
                }
            }
            swap[0] = dp.mnozina_sort[start][0];
            swap[1] = dp.mnozina_sort[start][1];
            dp.mnozina_sort[start][0] = dp.mnozina_sort[k][0];
            dp.mnozina_sort[start][1] = dp.mnozina_sort[k][1];
            dp.mnozina_sort[k][0] = swap[0];
            dp.mnozina_sort[k][1] = swap[1];

            quickSort(start, k - 1);
            quickSort(k + 1, end);
        } else {
            return;
        }
    }

    public void asort() {
        for (int i = 0; i < 81; i++) {
            dp.mnozina_sort[i][0] = i;
            dp.mnozina_sort[i][1] = dp.mnozina[i];
        }
        quickSort(0, 80);
    }

    public boolean zkontrolujMoznosti() {
        boolean status = true;
        for (int i = 0; i < 81; i++) {
            if (dp.hraci_deska[i / 9][i % 9] != 0) {
                if (!nastavHorizontalu(i / 9, dp.hraci_deska[i / 9][i % 9])) {
                    status = false;
                }
                if (!nastavVertikalu(i % 9, i, dp.hraci_deska[i / 9][i % 9])) {
                    status = false;
                }
                if (!nastavCtverec(i / 9, i % 9, dp.hraci_deska[i / 9][i % 9])) {
                    status = false;
                }
            }
        }
        return status;
    }

    public boolean kontrolaPole() {
        for (int i = 0; i < 81; i++) {
            if (dp.mnozina[i] < 0) {
                System.out.println("V poli se vyskytuje duplicita");
                System.exit(1);
            }
            if (dp.mnozina[i] == 0 && dp.hraci_deska[i / 9][i % 9] == 0) {
                return false;
            }
        }
        return true;
    }

    //pomocí rekurze vyřešíme větvení, kdy u nejtěžších hlavolamů přichází v úvahu zdánlivě více možností (Ariadnina nit)
    public boolean vyresPolicko(int pozice, boolean rekurze, boolean nahoda) {
        int tmp_hraci_deska[][] = new int[9][9];
        int tmp_mnozina[] = new int[81];
        int tmp_pole[][] = new int[81][10];
        for (int i = 1; i <= 9; i++) {
            if (nahoda) {
                i = generujPole(pozice);
            }
            if (dp.pole[pozice][i] == 1 || i == 0) {
                if (rekurze) {
                    for (int l = 0; l < 9; l++) {
                        for (int m = 0; m < 9; m++) {
                            tmp_hraci_deska[l][m] = dp.hraci_deska[l][m];
                        }
                    }
                    for (int l = 0; l < 81; l++) {
                        tmp_mnozina[l] = dp.mnozina[l];
                    }
                    for (int l = 0; l < 81; l++) {
                        for (int m = 0; m < 10; m++) {
                            tmp_pole[l][m] = dp.pole[l][m];
                        }
                    }
                }
                dp.hraci_deska[pozice / 9][pozice % 9] = i;
                nastavHorizontalu(pozice / 9, i);
                nastavVertikalu(pozice % 9, pozice, i);
                nastavCtverec(pozice / 9, pozice % 9, i);
                if (!rekurze) {
                    break;
                }

                if (rekurze) {
                    if (!vyresDesku()) {
                        for (int l = 0; l < 9; l++) {
                            for (int m = 0; m < 9; m++) {
                                dp.hraci_deska[l][m] = tmp_hraci_deska[l][m];
                            }
                        }
                        for (int l = 0; l < 81; l++) {
                            dp.mnozina[l] = tmp_mnozina[l];
                        }
                        for (int l = 0; l < 81; l++) {
                            for (int m = 0; m < 10; m++) {
                                dp.pole[l][m] = tmp_pole[l][m];
                            }
                        }
                        continue;
                    } else {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public boolean vyresDesku() {
        while (true) {
            zkontrolujMoznosti();
            if (!kontrolaPole()) {
                return false;
            }
            asort();

            boolean tip = true;
            if (dp.mnozina_sort[80][1] == 0) {
                break;
            }
            for (int i = 0; i < 81; i++) {
                int index = dp.mnozina_sort[i][0];
                if (tip && dp.mnozina_sort[i][1] > 1 && dp.hraci_deska[index / 9][index % 9] == 0) {
                    if (!vyresPolicko(index, true, false)) {
                        return false;
                    }
                    break;
                }
                if (dp.mnozina_sort[i][1] == 1 && dp.hraci_deska[index / 9][index % 9] == 0) {
                    vyresPolicko(index, false, false);
                    tip = false;
                }
            }
        }
        return true;
    }

    public void vymazatPolicka() {
        Random generator = new Random();
        int limit;
        int ctverec[][] = new int[9][2];

        for (int l = 0; l < 81; l += 27) {
            for (int m = 0; m < 9; m += 3) {
                limit = generator.nextInt(4);
                int iterace = 0;
                for (int p = 0; p < 3; p++) {
                    for (int q = 0; q < 27; q += 9) {
                        ctverec[iterace][0] = p + q;
                        ctverec[iterace][1] = 1;
                        iterace++;
                    }
                }

                for (int i = 0; i <= dp.difficulty + limit; i++) {
                    int poloha = generator.nextInt(9);
                    if (ctverec[poloha][1] == 0) {
                        i--;
                    } else {
                        ctverec[poloha][1] = 0;
                    }
                    dp.hraci_deska[(l + m + ctverec[poloha][0]) / 9][(l + m + ctverec[poloha][0]) % 9] = 0;
                }
            }
        }
    }

    public void nakopirujReseni() {
        for (int i = 0; i < 9; i++) {
            for (int j = 0; j < 9; j++) {
                dp.vyresena_deska[i][j] = dp.hraci_deska[i][j];
            }
        }
    }

    public void generujSudoku() {
        boolean status = true;
        while (status) {
            prednastavPole();
            for (int i = 0; i < 9; i++) {
                for (int j = 0; j < 9; j++) {
                    dp.hraci_deska[i][j] = 0;
                }
            }

            int vyreseno = 0;
            while (vyreseno != 81) {
                asort();
                for (int i = 0; i < 81; i++) {
                    int index = dp.mnozina_sort[i][0];
                    if (dp.hraci_deska[index / 9][index % 9] == 0) {
                        vyresPolicko(index, false, true);
                        vyreseno++;
                        break;
                    }
                }
            }

            for (int i = 0; i < 9; i++) {
                for (int j = 0; j < 9; j++) {
                    if (dp.hraci_deska[i][j] == 0) {
                        status = false;
                    }
                }
            }
            status = !status;
        }
        nakopirujReseni();
        vymazatPolicka();
    }

    public void zobrazDesku() {
        for (int i = 0; i < 9; i++) {
            for (int j = 0; j < 9; j++) {
                dp.hraci_deska[i][j] = dp.vyresena_deska[i][j];
            }
        }
        dp.repaint();
    }

//    public void nactiSoubor (String jmenoSouboru) throws FileNotFoundException, IOException {
//        SadaUsecek sada = new SadaUsecek();
//        FileReader fr = new FileReader(jmenoSouboru);
//        BufferedReader br = new BufferedReader(fr);
//        String line = br.readLine();
//        while(line!=null) {
//            if (!line.startsWith("#")) {
//                StringTokenizer st = new StringTokenizer(line);
//                String name = st.nextToken();
//                Point2D a = parsePoint(st.nextToken());
//                Point2D b = parsePoint(st.nextToken());
//                sada.pridejUsecku(new Usecka(name,a,b));
//            }
//            line = br.readLine();
//        }
//        fr.close();
//        return sada;
//    }
//    private Point2D parsePoint(String data) {
//        StringTokenizer st = new StringTokenizer(data, "();");
//        double x = Double.parseDouble(st.nextToken());
//        double y = Double.parseDouble(st.nextToken());
//        return new Point2D.Double(x,y);
//    }
//    public void ulozSoubor(SadaUsecekInterface su, String jmenoSouboru) throws IOException {
//        FileWriter fw = new FileWriter(jmenoSouboru);
//        PrintWriter pw = new PrintWriter(fw);
//
//        pw.println("#dsgfdsfg");
//        Iterator<Usecka> i = su.iterate();
//        while(i.hasNext()) {
//            Usecka u = i.next();
//            pw.print(u.getName());
//            pw.println(" "+printBod(u.getBodA())+" "+printBod(u.getBodB()));
//        }
//        fw.close();
//    }
//    private String printBod(Point2D p) {
//        return "("+p.getX()+";"+p.getY()+")";
//    }

    public static void main(String[] args) {
        JFrame f = new SwingApp();
        f.show();
    }
}
