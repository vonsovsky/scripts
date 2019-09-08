package lode;

import java.awt.*;
import java.awt.event.*;
import java.awt.event.ActionListener;
import java.util.Random;
import javax.swing.*;

public class LodeApp extends JFrame implements ActionListener, MouseListener, MouseMotionListener {
    Container cp;
    DrawingPanel dp = new DrawingPanel();
    private int nahodna_deska[] = new int[dp.ai.velikost_desky * dp.ai.velikost_desky];

    public LodeApp() {
        setTitle("Lodě");
        setSize(650, 360);
        setLocation(100, 100);

        addWindowListener(new WindowAdapter() { @Override public void windowClosing(WindowEvent e) { System.exit(0); } });

        dp.ai.faze = 1;

        generujLodě();
        cp = this.getContentPane();
        cp.add(dp);

        dp.ai.statusBar = new StatusBar();
        cp.add(dp.ai.statusBar, java.awt.BorderLayout.SOUTH);
        dp.ai.statusBar.setMessage("Umísti své lodě");

        cp.addMouseListener(this);
        addMouseListener(this);

        cp.addMouseMotionListener(this);
        addMouseMotionListener(this);

        dp.ai.strelbaPozice[0][0] = -1;
        dp.ai.dalsiVystrel[0] = -1;
    }

    public void zamichejPole() {
        Random generator = new Random();
        int counter = 0, tmp;
        for (int i = 0; i < dp.ai.velikost_desky; i++) {
            for (int j = 0; j < dp.ai.velikost_desky; j++) {
                nahodna_deska[counter] = i * dp.ai.velikost_desky + j;
                counter++;
            }
        }

        for (int i = 0; i < nahodna_deska.length; i++) {
            int random = generator.nextInt(nahodna_deska.length);
            tmp = nahodna_deska[random];
            nahodna_deska[random] = nahodna_deska[i];
            nahodna_deska[i] = tmp;
        }
    }

    public void generujLodě() {
        Random generator = new Random();
        zamichejPole();

        int counter = 0;
        int generator_lodi[] = new int[dp.ai.ai_flotila.ctverce + dp.ai.ai_flotila.obdelniky +
                                       dp.ai.ai_flotila.krizniky + dp.ai.ai_flotila.ponorka];
        for (int i = 0; i < dp.ai.ai_flotila.ctverce; i++) {
            generator_lodi[counter] = 1;
            counter++;
        }
        for (int i = 0; i < dp.ai.ai_flotila.obdelniky; i++) {
            generator_lodi[counter] = 2;
            counter++;
        }
        for (int i = 0; i < dp.ai.ai_flotila.krizniky; i++) {
            generator_lodi[counter] = 3;
            counter++;
        }
        for (int i = 0; i < dp.ai.ai_flotila.ponorka; i++) {
            generator_lodi[counter] = 4;
            counter++;
        }

        //lodě ještě zamícháme, abychom je umisťovali v náhodném pořadí
        int tmp;
        for (int i = 0; i < generator_lodi.length; i++) {
            int random = generator.nextInt(generator_lodi.length);
            tmp = generator_lodi[random];
            generator_lodi[random] = generator_lodi[i];
            generator_lodi[i] = tmp;
        }

        //najdiVolneMisto(ai_flotila.ponorka_velikost[0], ai_flotila.ponorka_velikost[1], 4);
        int rozmer1 = 0, rozmer2 = 0;
        for (int i = 0; i < generator_lodi.length; i++) {
            if (generator_lodi[i] == 1) {
                rozmer1 = dp.ai.ai_flotila.ctverce_velikost[0];
                rozmer2 = dp.ai.ai_flotila.ctverce_velikost[1];
            }
            if (generator_lodi[i] == 2) {
                rozmer1 = dp.ai.ai_flotila.obdelniky_velikost[0];
                rozmer2 = dp.ai.ai_flotila.obdelniky_velikost[1];
            }
            if (generator_lodi[i] == 3) {
                rozmer1 = dp.ai.ai_flotila.krizniky_velikost[0];
                rozmer2 = dp.ai.ai_flotila.krizniky_velikost[1];
            }
            if (generator_lodi[i] == 4) {
                rozmer1 = dp.ai.ai_flotila.ponorka_velikost[0];
                rozmer2 = dp.ai.ai_flotila.ponorka_velikost[1];
            }

            if (!najdiVolneMisto(rozmer1, rozmer2, generator_lodi[i])) {
                for (int k = 0; k < dp.ai.velikost_desky; k++)
                    for (int j = 0; j < dp.ai.velikost_desky; j++)
                        dp.ai.ai_deska[k][j] = 0;
                i = -1;
            }
        }
    }

    public boolean najdiVolneMisto(int delka, int sirka, int objekt) {
        Random generator = new Random();
        int smery[] = {1, 2, 3, 4};
        int x, y, tmp;
        int[] returnArray = new int[4];
        for (int i = 0; i < smery.length; i++) {
            int random = generator.nextInt(smery.length);
            tmp = smery[random];
            smery[random] = smery[i];
            smery[i] = tmp;
        }

        for (int j = 0; j < nahodna_deska.length; j++) {
            x = nahodna_deska[j] % dp.ai.velikost_desky;
            y = nahodna_deska[j] / dp.ai.velikost_desky;
            if (dp.ai.ai_deska[y][x] == 1 || dp.ai.ai_deska[y][x] == 3) continue;

            for (int i = 0; i < 4; i++) {
                int rozmer1, rozmer2;
                if (smery[i] == 1) {
                    rozmer1 = sirka;
                    rozmer2 = delka;
                } else {
                    rozmer1 = delka;
                    rozmer2 = sirka;
                }

                int volno = rozmer1;

                if (smery[i] <= 2 && y <= rozmer2 - 3) continue;
                if (smery[i] > 2 && y + rozmer2 >= 12) continue;

                for (int k = x - rozmer1 + 1; k < x + rozmer1; k++) {
                    if (k < 0 || k > dp.ai.velikost_desky - 1) {
                        if (k == -1 || k == dp.ai.velikost_desky) volno--;
                        if (volno == 0) {
                            if (smery[i] <= 2) {
                                returnArray[0] = k - rozmer1 + 1; returnArray[1] = y - rozmer2 + 1; returnArray[2] = k; returnArray[3] = y;
                            } else {
                                returnArray[0] = k - rozmer1 + 1; returnArray[1] = y; returnArray[2] = k; returnArray[3] = y + rozmer2 - 1;
                            }
                            vyznacOhraniceni(returnArray, objekt);
                            return true;
                        }
                        continue;
                    }
                    boolean status = true;
                    if (smery[i] == 1 || smery[i] == 2) {
                        for (int l = y; l > y - rozmer2; l--) {
                            if (l >= 0 && l <= dp.ai.velikost_desky - 1) {
                                if (dp.ai.ai_deska[l][k] == 1) status = false;
                            }
                        }
                    }
                    if (smery[i] == 3 || smery[i] == 4) {
                        for (int l = y; l < y + rozmer2; l++) {
                            if (l >= 0 && l <= dp.ai.velikost_desky - 1) {
                                if (dp.ai.ai_deska[l][k] == 1) status = false;
                            }
                        }
                    }
                    if (status) volno--;
                    else volno = rozmer1;
                    if (volno == 0) {
                        if (smery[i] <= 2) {
                            returnArray[0] = k - rozmer1 + 1; returnArray[1] = y - rozmer2 + 1; returnArray[2] = k; returnArray[3] = y;
                        } else {
                            returnArray[0] = k - rozmer1 + 1; returnArray[1] = y; returnArray[2] = k; returnArray[3] = y + rozmer2 - 1;
                        }
                        vyznacOhraniceni(returnArray, objekt);
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public void vyznacOhraniceni(int[] hranice, int objekt) {
        Random generator = new Random();
        int modify = generator.nextInt(2);
        switch (objekt) {
            case 1:
                dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 1] = 1;
                break;
            case 2:
                //svisle
                if (hranice[3] - hranice[1] > hranice[2] - hranice[0]) {
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 1] = 1;
                    dp.ai.ai_deska[hranice[1] + 2][hranice[0] + 1] = 1;
                //vodorovně
                } else {
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 1] = 1;
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 2] = 1;
                }
                break;
            case 3:
                //svisle
                if (hranice[3] - hranice[1] > hranice[2] - hranice[0]) {
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 1 + modify] = 1;
                    dp.ai.ai_deska[hranice[1] + 2][hranice[0] + 1] = 1;
                    dp.ai.ai_deska[hranice[1] + 2][hranice[0] + 2] = 1;
                    dp.ai.ai_deska[hranice[1] + 3][hranice[0] + 1 + modify] = 1;
                //vodorovně
                } else {
                    dp.ai.ai_deska[hranice[1] + 1 + modify][hranice[0] + 1] = 1;
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 2] = 1;
                    dp.ai.ai_deska[hranice[1] + 2][hranice[0] + 2] = 1;
                    dp.ai.ai_deska[hranice[1] + 1 + modify][hranice[0] + 3] = 1;
                    //ke křižníku se mohou lodě přiblížit trochu více díky vykrojení
                }
                break;
            case 4:
                //svisle
                if (hranice[3] - hranice[1] > hranice[2] - hranice[0]) {
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 1] = 1;
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 2] = 1;
                    dp.ai.ai_deska[hranice[1] + 4][hranice[0] + 1] = 1;
                    dp.ai.ai_deska[hranice[1] + 4][hranice[0] + 2] = 1;
                    dp.ai.ai_deska[hranice[1] + 2][hranice[0] + 1 + modify] = 1;
                    dp.ai.ai_deska[hranice[1] + 3][hranice[0] + 1 + modify] = 1;
                //vodorovně
                } else {
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 1] = 1;
                    dp.ai.ai_deska[hranice[1] + 2][hranice[0] + 1] = 1;
                    dp.ai.ai_deska[hranice[1] + 1][hranice[0] + 4] = 1;
                    dp.ai.ai_deska[hranice[1] + 2][hranice[0] + 4] = 1;
                    dp.ai.ai_deska[hranice[1] + 1 + modify][hranice[0] + 2] = 1;
                    dp.ai.ai_deska[hranice[1] + 1 + modify][hranice[0] + 3] = 1;

                }
                break;
        }
    }

    public void actionPerformed(ActionEvent e) {
    }

    public static void main(String[] args) {
        JFrame f = new LodeApp();
        f.show();
    }

    public void mouseClicked(MouseEvent e) {
        if (e.getButton() == e.BUTTON1) {
            if (dp.ai.faze == 1 && dp.dragging) dp.put = true;
            if (dp.ai.faze == 2) dp.fire = true;
        }
        if (e.getButton() == e.BUTTON3) {
            if (dp.ai.faze == 1) {
                dp.smer++;
                if (dp.smer == 5) dp.smer = 1;
            } else if (dp.ai.faze == 2) {
                if (dp.mark[(e.getY() - 5) / 30][(e.getX() - 325) / 30] == 0)
                    dp.mark[(e.getY() - 5) / 30][(e.getX() - 325) / 30] = 1;
                else if (dp.mark[(e.getY() - 5) / 30][(e.getX() - 325) / 30] == 1)
                    dp.mark[(e.getY() - 5) / 30][(e.getX() - 325) / 30] = 2;
            }
        }
        dp.repaint();
    }

    @SuppressWarnings("static-access")
    public void mousePressed(MouseEvent e) {
    }

    public void mouseReleased(MouseEvent e) {
    }

    public void mouseEntered(MouseEvent e) {
    }

    public void mouseExited(MouseEvent e) {
    }

    public void mouseDragged(MouseEvent e) {
    }

    public void mouseMoved(MouseEvent e) {
        dp.drop_x = e.getX();
        dp.drop_y = e.getY();
        dp.repaint();
    }
}
