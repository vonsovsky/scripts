package lode;

import java.util.Random;

class flotila {
    public int ctverce = 4;
    public int obdelniky = 2;
    public int krizniky = 3;
    public int ponorka = 1;
    public int ctverce_velikost[] = {3, 3};
    public int obdelniky_velikost[] = {4, 3};
    public int krizniky_velikost[] = {5, 4};
    public int ponorka_velikost[] = {6, 4};
    protected int x;
    protected int y;
    protected boolean sever;
    protected boolean vychod;
    protected boolean jih;
    protected boolean zapad;
}

public class AI extends Thread {
    public int velikost_desky = 10;

    public int ai_deska[][] = new int[velikost_desky][velikost_desky];
    public int hu_deska[][] = new int[velikost_desky][velikost_desky];
    /*
    public int hu_deska[][] = {{0, 1, 1, 0, 0, 1, 0, 0, 1, 0},
                               {0, 1, 0, 0, 1, 1, 1, 0, 1, 1},
                               {0, 1, 0, 0, 0, 0, 0, 0, 1, 0},
                               {0, 1, 1, 0, 0, 0, 0, 0, 0, 0},
                               {0, 0, 0, 0, 0, 0, 0, 0, 0, 0},
                               {0, 0, 0, 0, 1, 0, 1, 0, 0, 0},
                               {0, 0, 0, 0, 1, 0, 1, 0, 0, 1},
                               {0, 0, 1, 0, 0, 0, 0, 0, 0, 0},
                               {0, 0, 1, 1, 0, 1, 0, 0, 0, 1},
                               {0, 0, 1, 0, 0, 0, 0, 1, 0, 0}};*/

    public StatusBar statusBar;
    public flotila hu_flotila = new flotila();
    public flotila ai_flotila = new flotila();
    public int strelbaPozice[][] = new int[8][5];
    public int dalsiVystrel[] = new int[5];
    public boolean znovu = false;
    public int faze = 1;

    public void run() {
        aiTah();
    }

    public void sleep() {
        try {
            Thread.currentThread().sleep(1000);
        } catch (InterruptedException IE) {
        }
    }

    private int zjistiHorizontalniPocet(int y, int x) {
        int horizontalni = 0;
        while (x >= 0 && hu_deska[y][x] == 2) {
            horizontalni++;
            x--;
        }
        x += horizontalni + 1;
        while (x < velikost_desky && hu_deska[y][x] == 2) {
            horizontalni++;
            x++;
        }
        return horizontalni;
    }

    private int zjistiVertikalniPocet(int y, int x) {
        int vertikalni = 0;
        while (y >= 0 && hu_deska[y][x] == 2) {
            vertikalni++;
            y--;
        }
        y += vertikalni + 1;
        while (y < velikost_desky && hu_deska[y][x] == 2) {
            vertikalni++;
            y++;
        }
        return vertikalni;
    }

    private void zablokovatStrany(int smer, int y, int x) {
        if (y < 0 || y >= velikost_desky || x < 0 || x >= velikost_desky) return;
        int counter = 0;
        while (strelbaPozice[counter][0] != -1) {
            if (strelbaPozice[counter][0] == y * velikost_desky + x) break;
            counter++;
        }

        //pokud je hledaný bod již uzavřeným uzlem
        if (strelbaPozice[counter][0] == -1) return;

        strelbaPozice[counter][smer] = 0;
        strelbaPozice[counter][smer + 2] = 0;

        //pokud jsou všechny směry nepřístupně, pojďme bod uzavřít
        if (strelbaPozice[counter][1] == 0 && strelbaPozice[counter][2] == 0 && strelbaPozice[counter][3] == 0 && strelbaPozice[counter][4] == 0)
            strelbaPozice[counter][0] = 255;
    }

    private void zkontrolujOstatni(int mimo_index) {
        int counter = 0;
        while (strelbaPozice[counter][0] != -1) {
            if (counter == mimo_index || strelbaPozice[counter][0] == 255) {
                counter++;
                continue;
            }
            int y = strelbaPozice[counter][0] / velikost_desky;
            int x = strelbaPozice[counter][0] % velikost_desky;
            if (y == 0 || hu_deska[y - 1][x] != 0 && hu_deska[y - 1][x] != 1) strelbaPozice[counter][1] = 0;
            if (x == velikost_desky - 1 || hu_deska[y][x + 1] != 0 && hu_deska[y][x + 1] != 1) strelbaPozice[counter][2] = 0;
            if (y == velikost_desky - 1 || hu_deska[y + 1][x] != 0 && hu_deska[y + 1][x] != 1) strelbaPozice[counter][3] = 0;
            if (x == 0 || hu_deska[y][x - 1] != 0 && hu_deska[y][x - 1] != 1) strelbaPozice[counter][4] = 0;

            //pokud jsou všechny směry nepřístupně, pojďme bod uzavřít
            if (strelbaPozice[counter][1] == 0 && strelbaPozice[counter][2] == 0 &&
                strelbaPozice[counter][3] == 0 && strelbaPozice[counter][4] == 0)
                strelbaPozice[counter][0] = 255;
            counter++;
        }
    }

    private void pridejPozici(int y, int x) {
        int counter = 0;
        while (strelbaPozice[counter][0] != -1) counter++;
        strelbaPozice[counter + 1][0] = -1;
        strelbaPozice[counter][0] = y * velikost_desky + x;

        if (y == 0 || hu_deska[y - 1][x] != 0 && hu_deska[y - 1][x] != 1) strelbaPozice[counter][1] = 0;
        else strelbaPozice[counter][1] = 1;
        if (x == velikost_desky - 1 || hu_deska[y][x + 1] != 0 && hu_deska[y][x + 1] != 1) strelbaPozice[counter][2] = 0;
        else strelbaPozice[counter][2] = 1;
        if (y == velikost_desky - 1 || hu_deska[y + 1][x] != 0 && hu_deska[y + 1][x] != 1) strelbaPozice[counter][3] = 0;
        else strelbaPozice[counter][3] = 1;
        if (x == 0 || hu_deska[y][x - 1] != 0 && hu_deska[y][x - 1] != 1) strelbaPozice[counter][4] = 0;
        else strelbaPozice[counter][4] = 1;

        zkontrolujOstatni(counter);

        int horizontalni = zjistiHorizontalniPocet(y, x);
        int vertikalni = zjistiVertikalniPocet(y, x);
        //pokud není již přítomna ponorka, maximální velikost může být 3 políčka
        if (hu_flotila.ponorka == 0) {
            if (horizontalni == 3) {
                strelbaPozice[counter][2] = 0;
                strelbaPozice[counter][4] = 0;
                if (x < velikost_desky - 1 && hu_deska[y][x + 1] == 2) zablokovatStrany(2, y, x + 2);
                else zablokovatStrany(2, y, x - 2);
            }
            if (vertikalni == 3) {
                strelbaPozice[counter][1] = 0;
                strelbaPozice[counter][3] = 0;
                if (y < velikost_desky - 1 && hu_deska[y + 1][x] == 2) zablokovatStrany(1, y + 2, x);
                else zablokovatStrany(1, y - 2, x);
            }
        } else {
            //testujeme, zda jsme nenačali ponorku, bohužel pro oba směry to vychází po osmi kombinacích
            if (horizontalni == 3) {
                counter = 0;
                while (dalsiVystrel[counter] != -1) counter++;
                //x
                //yxx
                if (x < velikost_desky - 1 && hu_deska[y][x + 1] == 2 && y > 0 && hu_deska[y - 1][x] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + x + 3;
                    dalsiVystrel[counter + 1] = (y - 1) * velikost_desky + x + 3;
                    dalsiVystrel[counter + 2] = -1;
                }
                //x
                //xxy
                if (x > 0 && hu_deska[y][x - 1] == 2 && y > 0 && hu_deska[y - 1][x - 2] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + x + 1;
                    dalsiVystrel[counter + 1] = (y - 1) * velikost_desky + x + 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                //  x
                //xxy
                if (x > 0 && hu_deska[y][x - 1] == 2 && y > 0 && hu_deska[y - 1][x] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + x - 3;
                    dalsiVystrel[counter + 1] = (y - 1) * velikost_desky + x - 3;
                    dalsiVystrel[counter + 2] = -1;
                }
                //  x
                //yxx
                if (x < velikost_desky - 1 && hu_deska[y][x + 1] == 2 && y > 0 && hu_deska[y - 1][x + 2] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + x - 1;
                    dalsiVystrel[counter + 1] = (y - 1) * velikost_desky + x - 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                //yxx
                //x
                if (x < velikost_desky - 1 && hu_deska[y][x + 1] == 2 && y < velikost_desky - 1 && hu_deska[y + 1][x] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + x + 3;
                    dalsiVystrel[counter + 1] = (y + 1) * velikost_desky + x + 3;
                    dalsiVystrel[counter + 2] = -1;
                }
                //xxy
                //x
                if (x > 0 && hu_deska[y][x - 1] == 2 && y < velikost_desky - 1 && hu_deska[y + 1][x - 2] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + x + 1;
                    dalsiVystrel[counter + 1] = (y + 1) * velikost_desky + x + 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                //xxy
                //  x
                if (x > 0 && hu_deska[y][x - 1] == 2 && y < velikost_desky - 1 && hu_deska[y + 1][x] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + x - 3;
                    dalsiVystrel[counter + 1] = (y + 1) * velikost_desky + x - 3;
                    dalsiVystrel[counter + 2] = -1;
                }
                //yxx
                //  x
                if (x < velikost_desky - 1 && hu_deska[y][x + 1] == 2 && y < velikost_desky - 1 && hu_deska[y + 1][x + 2] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + x - 1;
                    dalsiVystrel[counter + 1] = (y + 1) * velikost_desky + x - 1;
                    dalsiVystrel[counter + 2] = -1;
                }
            }
            if (vertikalni == 3) {
                counter = 0;
                while (dalsiVystrel[counter] != -1) counter++;
                //xy
                // x
                // x
                if (y < velikost_desky - 1 && hu_deska[y + 1][x] == 2 && x > 0 && hu_deska[y][x - 1] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + y + 3;
                    dalsiVystrel[counter + 1] = (y + 3) * velikost_desky + x - 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                //xx
                // x
                // y
                if (y > 0 && hu_deska[y - 1][x] == 2 && x > 0 && hu_deska[y - 2][x - 1] == 2) {
                    dalsiVystrel[counter] = (y + 1) * velikost_desky + x;
                    dalsiVystrel[counter + 1] = (y + 1) * velikost_desky + x - 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                // x
                // x
                //xy
                if (y > 0 && hu_deska[y - 1][x] == 2 && x > 0 && hu_deska[y][x - 1] == 2) {
                    dalsiVystrel[counter] = (y - 3) * velikost_desky + x;
                    dalsiVystrel[counter + 1] = (y - 3) * velikost_desky + x - 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                // y
                // x
                //xx
                if (y < velikost_desky - 1 && hu_deska[y + 1][x] == 2 && x > 0 && hu_deska[y + 2][x - 1] == 2) {
                    dalsiVystrel[counter] = (y - 1) * velikost_desky + x;
                    dalsiVystrel[counter + 1] = (y - 1) * velikost_desky + x - 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                //yx
                //x
                //x
                if (y < velikost_desky - 1 && hu_deska[y + 1][x] == 2 && x < velikost_desky - 1 && hu_deska[y][x + 1] == 2) {
                    dalsiVystrel[counter] = y * velikost_desky + y + 3;
                    dalsiVystrel[counter + 1] = (y + 3) * velikost_desky + x + 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                //xx
                //x
                //y
                if (y > 0 && hu_deska[y - 1][x] == 2 && x < velikost_desky - 1 && hu_deska[y - 2][x + 1] == 2) {
                    dalsiVystrel[counter] = (y + 1) * velikost_desky + x;
                    dalsiVystrel[counter + 1] = (y + 1) * velikost_desky + x + 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                //x
                //x
                //yx
                if (y > 0 && hu_deska[y - 1][x] == 2 && x < velikost_desky - 1 && hu_deska[y][x + 1] == 2) {
                    dalsiVystrel[counter] = (y - 3) * velikost_desky + x;
                    dalsiVystrel[counter + 1] = (y - 3) * velikost_desky + x + 1;
                    dalsiVystrel[counter + 2] = -1;
                }
                //y
                //x
                //xx
                if (y < velikost_desky - 1 && hu_deska[y + 1][x] == 2 && x < velikost_desky - 1 && hu_deska[y + 2][x + 1] == 2) {
                    dalsiVystrel[counter] = (y - 1) * velikost_desky + x;
                    dalsiVystrel[counter + 1] = (y - 1) * velikost_desky + x + 1;
                    dalsiVystrel[counter + 2] = -1;
                }
            }
            if (horizontalni == 4) {
                strelbaPozice[counter][2] = 0;
                strelbaPozice[counter][4] = 0;
                if (x < velikost_desky - 1 && hu_deska[y][x + 1] == 2) {
                    zablokovatStrany(2, y, x + 3);
                    zablokovatStrany(1, y - 1, x + 1);
                    zablokovatStrany(1, y + 1, x + 1);
                    zablokovatStrany(1, y - 1, x + 2);
                    zablokovatStrany(1, y + 1, x + 2);
                } else {
                    zablokovatStrany(2, y, x - 3);
                    zablokovatStrany(1, y - 1, x - 1);
                    zablokovatStrany(1, y + 1, x - 1);
                    zablokovatStrany(1, y - 1, x - 2);
                    zablokovatStrany(1, y + 1, x - 2);
                }
            }
            if (vertikalni == 4) {
                strelbaPozice[counter][1] = 0;
                strelbaPozice[counter][3] = 0;
                if (y < velikost_desky - 1 && hu_deska[y + 1][x] == 2) {
                    zablokovatStrany(2, y + 3, x);
                    zablokovatStrany(1, y + 1, x - 1);
                    zablokovatStrany(1, y + 1, x + 1);
                    zablokovatStrany(1, y + 2, x - 1);
                    zablokovatStrany(1, y + 2, x + 1);
                } else {
                    zablokovatStrany(2, y - 3, x);
                    zablokovatStrany(1, y - 1, x - 1);
                    zablokovatStrany(1, y - 1, x + 1);
                    zablokovatStrany(1, y - 2, x - 1);
                    zablokovatStrany(1, y - 2, x + 1);
                }
            }
        }

        //pokud jsou všechny směry nepřístupně, pojďme bod uzavřít
        if (strelbaPozice[counter][1] == 0 && strelbaPozice[counter][2] == 0 && strelbaPozice[counter][3] == 0 && strelbaPozice[counter][4] == 0)
            strelbaPozice[counter][0] = 255;
    }

    //vybereme jeden z možných bloků lodi, která je aktuálně pod útokem
    private int pripravPole() {
        Random generator = new Random();
        int poleIndexu[] = new int[7];
        int counter = 0, counterIndex = 0, pocet, nahoda;

        while (strelbaPozice[counter][0] != -1) {
            if (strelbaPozice[counter][0] != 255) {
                poleIndexu[counterIndex] = counter;
                counterIndex++;
            }
            counter++;
        }
        pocet = counterIndex;

        return poleIndexu[generator.nextInt(pocet)];
    }

    private int pripravSmer(int index) {
        Random generator = new Random();
        int smerovePole[] = new int[4];
        int counter = 0;

        for (int i = 1; i <= 4; i++) {
            if (strelbaPozice[index][i] == 1) {
                smerovePole[counter] = i;
                counter++;
            }
        }

        if (counter == 0)
            System.out.println("Kdo propustil uzavřený bod?");

        return smerovePole[generator.nextInt(counter)];
    }

    public void střelba(int y, int x) {
        //počítač se trefil
        if (hu_deska[y][x] == 1) {
            hu_deska[y][x] = 2;
            if (potopen(y, x, false)) {
                potopen(y, x, 1);
                strelbaPozice[0][0] = -1;
                dalsiVystrel[0] = -1;
                //funkci znovu provedeme až z ní vylezeme - vyhýbáme se rekurzivnosti
                znovu = true;
            } else {
                pridejPozici(y, x);
                znovu = true;
            }
        } else {
            hu_deska[y][x] = 4;
            znovu = false;
        }
    }

    public int dalsiTah() {
        int counter = 0;
        while (dalsiVystrel[counter] != -1) {
            if (dalsiVystrel[counter] != -2) {
                int tmp = dalsiVystrel[counter];
                dalsiVystrel[counter] = -2;
                return tmp;
            }
            counter++;
        }
        return -1;
    }

    private int najdiVolneMisto() {
        Random generator = new Random();
        int mapa[] = new int[velikost_desky * velikost_desky];
        int counter = 0;

        for (int y = 0; y < velikost_desky; y++) {
            for (int x = 0; x < velikost_desky; x++) {
                if (hu_deska[y][x] == 0 || hu_deska[y][x] == 1) {
                    mapa[counter] = y * velikost_desky + x;
                    counter++;
                }
            }
        }
        System.out.println(counter);

        if (counter == 0) return -1;
        return mapa[generator.nextInt(counter)];
    }

    public void potopen(int y, int x, int oznacit) {
        potopen(y, x, false, true);
    }

    public boolean potopen(int y, int x, boolean utokNaPC) {
        return potopen(y, x, utokNaPC, false);
    }

    public boolean potopen(int y, int x, boolean utokNaPC, boolean oznacit) {
        //procházíme trupem zasažené lodi pomocí simulace fronty
        //pozor maximální velikost fronty se odvíjí od největší lodě
        int fronta[][] = new int[8][3];
        int ukazatel = 0, peek = 0, border_lefttop_y, border_lefttop_x, border_rightbottom_y, border_rightbottom_x;
        boolean status = true;

        fronta[ukazatel][0] = y; fronta[ukazatel][1] = x; fronta[ukazatel][2] = 1;
        if (utokNaPC) ai_deska[y][x] = 3;
        else hu_deska[y][x] = 3;
        while (fronta[peek][2] != 0) {
            border_lefttop_y = fronta[peek][0] - 1; border_lefttop_x = fronta[peek][1] - 1;
            border_rightbottom_y = fronta[peek][0] + 1; border_rightbottom_x = fronta[peek][1] + 1;
            if (border_lefttop_y < 0) border_lefttop_y = 0;
            if (border_lefttop_x < 0) border_lefttop_x = 0;
            if (border_rightbottom_y > velikost_desky - 1) border_rightbottom_y = velikost_desky - 1;
            if (border_rightbottom_x > velikost_desky - 1) border_rightbottom_x = velikost_desky - 1;
            for (int i = border_lefttop_y; i <= border_rightbottom_y; i++) {
                for (int j = border_lefttop_x; j <= border_rightbottom_x; j++) {
                    if (i == fronta[peek][0] && j == fronta[peek][1]) continue;
                    if (utokNaPC) {
                        if (ai_deska[i][j] == 1) status = false;
                        if (ai_deska[i][j] == 2) {
                            ukazatel++;
                            fronta[ukazatel][0] = i; fronta[ukazatel][1] = j; fronta[ukazatel][2] = 1;
                            ai_deska[i][j] = 3;
                        }
                    } else {
                        if (hu_deska[i][j] == 1) status = false;
                        if (hu_deska[i][j] == 2) {
                            ukazatel++;
                            fronta[ukazatel][0] = i; fronta[ukazatel][1] = j; fronta[ukazatel][2] = 1;
                            hu_deska[i][j] = 3;
                        }
                        if (oznacit && hu_deska[i][j] == 0) hu_deska[i][j] = 5;
                    }
                if (!status) break;
                }
            if (!status) break;
            }
        if (!status) break;
        peek++;
        }

        for (int i = 0; i < velikost_desky; i++) {
            for (int j = 0; j < velikost_desky; j++) {
                if (utokNaPC && ai_deska[i][j] == 3) ai_deska[i][j] = 2;
                if (!utokNaPC && hu_deska[i][j] == 3) hu_deska[i][j] = 2;
            }
        }

        if (status) {
            if (peek == 1) {
                if (utokNaPC) ai_flotila.ctverce--;
                else hu_flotila.ctverce--;
            }
            if (peek == 2) {
                if (utokNaPC) ai_flotila.obdelniky--;
                else hu_flotila.obdelniky--;
            }
            if (peek == 4) {
                if (utokNaPC) ai_flotila.krizniky--;
                else hu_flotila.krizniky--;
            }
            if (peek == 6) {
                if (utokNaPC) ai_flotila.ponorka--;
                else hu_flotila.ponorka--;
            }
            if (utokNaPC) {
                if (ai_flotila.ctverce == 0 && ai_flotila.obdelniky == 0 && ai_flotila.krizniky == 0 && ai_flotila.ponorka == 0) {
                    statusBar.setMessage("Zvítězil jsi");
                    faze = 3;
                }
            } else {
                if (hu_flotila.ctverce == -4 && hu_flotila.obdelniky == -2 && hu_flotila.krizniky == -3 && hu_flotila.ponorka == -1) {
                    statusBar.setMessage("Zvítězil počítač");
                    faze = 3;
                }
            }
        }

        return status;
    }

    public void aiTah() {
        int x, y;

        if (strelbaPozice[0][0] == -1) {
            int pozice = najdiVolneMisto();
            if (pozice == -1) {
                znovu = false;
                return;
            }
            x = pozice % velikost_desky;
            y = pozice / velikost_desky;
            střelba(y, x);
        } else {
            int dalsiTah = dalsiTah();
            if (dalsiTah == -1) {
                int index = pripravPole();
                int smery = pripravSmer(index);
                x = strelbaPozice[index][0] % velikost_desky;
                y = strelbaPozice[index][0] / velikost_desky;
                strelbaPozice[index][smery] = 0;
                if (smery == 1) střelba(y - 1, x);
                if (smery == 2) střelba(y, x + 1);
                if (smery == 3) střelba(y + 1, x);
                if (smery == 4) střelba(y, x - 1);

                //pokud jsou všechny směry nepřístupně, pojďme bod uzavřít
                if (strelbaPozice[0][0] != -1)
                    if (strelbaPozice[index][1] == 0 && strelbaPozice[index][2] == 0 &&
                        strelbaPozice[index][3] == 0 && strelbaPozice[index][4] == 0)
                        strelbaPozice[index][0] = 255;
            } else střelba(dalsiTah / velikost_desky, dalsiTah % velikost_desky);
        }
    }
}
