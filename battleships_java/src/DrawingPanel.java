package lode;

import java.awt.*;
import javax.swing.*;

public class DrawingPanel extends JPanel {
    AI ai = new AI();
    private int velikost_desky = ai.velikost_desky;


    //značkovací deska
    public int mark[][] = new int[velikost_desky][velikost_desky];

    public boolean fire = false;
    public int drop_x = 0;
    public int drop_y = 0;
    public boolean dragging = true;
    public boolean put = false;
    public int smer = 1;
    public int lod = 4;
    public int pocet_lodi = 0;

    private Color not_possible = Color.red;
    private Color is_possible = Color.green;
    private Color sea = new Color(17, 143, 202);
    private Color window = new Color(238, 238, 238);
    private int x_box, y_box, x_oldbox, y_oldbox = -1, smer_old;
    private flotila lode = new flotila();

    @Override
    public void paintComponent(Graphics g) {
        g.setColor(Color.green);
        //první deska
        for (int i = 5; i <= 5 + 30 * velikost_desky; i += 30) {
            g.drawLine(325, i, 325 + 30 * velikost_desky, i);
        }
        for (int j = 5; j <= 5 + 30 * velikost_desky; j += 30) {
            g.drawLine(j, 5, j, 5 + 30 * velikost_desky);
        }

        //druhá deska
        for (int i = 5; i <= 5 + 30 * velikost_desky; i += 30) {
            g.drawLine(5, i, 5 + 30 * velikost_desky, i);
        }
        for (int j = 325; j <= 325 + 30 * velikost_desky; j += 30) {
            g.drawLine(j, 5, j, 5 + 30 * velikost_desky);
        }

        for (int i = 0; i < velikost_desky; i++) {
            for (int j = 0; j < velikost_desky; j++) {
                g.setColor(Color.blue);
                if (ai.hu_deska[i][j] == 1) g.fillRect(6 + j * 30, 6 + i * 30, 29, 29);
                g.setColor(not_possible);
                if (ai.hu_deska[i][j] == 2) g.fillRect(6 + j * 30, 6 + i * 30, 29, 29);
                g.setColor(sea);
                if (ai.hu_deska[i][j] == 4) g.fillRect(6 + j * 30, 6 + i * 30, 29, 29);
                g.setColor(is_possible);
                if (mark[i][j] == 2) g.setColor(window);
                if (mark[i][j] == 1 || mark[i][j] == 2) g.fillRect(326 + j * 30, 6 + i * 30, 29, 29);
                if (mark[i][j] == 2) mark[i][j] = 0;
            }
        }

        if (fire && drop_x >= 325 && drop_x < 325 + 30 * velikost_desky && drop_y >= 5 && drop_y < 5 + 30 * velikost_desky) {
            x_box = (drop_x - 325) / 30;
            y_box = (drop_y - 5) / 30;
            mark[y_box][x_box] = 3;
            if (ai.ai_deska[y_box][x_box] == 0 || ai.ai_deska[y_box][x_box] == 1) {
                //zasáhli jsme loď, ještě ověřit zda celou
                if (ai.ai_deska[y_box][x_box] == 1) {
                    g.setColor(not_possible);
                    ai.ai_deska[y_box][x_box] = 2;
                    if (!ai.potopen(y_box, x_box, true)) ai.statusBar.setMessage("Zásah");
                    else ai.statusBar.setMessage("Zásah, potopen");
                //nic jsme netrefili
                } else {
                    ai.statusBar.setMessage("Volné moře");
                    g.setColor(sea);
                    g.fillRect(326 + x_box * 30, 6 + y_box * 30, 29, 29);

                    ai.ai_deska[y_box][x_box] = 4;
                    ai.znovu = true;
                    repaint();
                    while (ai.znovu) {
                        ai.run();
                        //ai.sleep();
                        repaint();
                        /*
                        try {
                            
                            Thread.currentThread().sleep(1000);
                        } catch(InterruptedException ie) {
                        }
                        */
                    }
                }
                g.fillRect(326 + x_box * 30, 6 + y_box * 30, 29, 29);
            } else ai.statusBar.setMessage("Neplatný tah");
            fire = false;
        }

        if (ai.faze == 1 && drop_x >= 5 && drop_x < 5 + 30 * velikost_desky && drop_y >= 5 && drop_y < 5 + 30 * velikost_desky) {
            x_box = (drop_y - 5) / 30;
            y_box = (drop_x - 5) / 30;

            g.setColor(window);
            if (lod == 1) {
                if (y_oldbox != -1 && (x_oldbox != x_box || y_oldbox != y_box)) g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                if (overitKolizi(x_box - 1, x_box + 1, y_box - 1, y_box + 1)) g.setColor(is_possible);
                else g.setColor(not_possible);
                g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                if (put) {
                    if (overitKolizi(x_box - 1, x_box + 1, y_box - 1, y_box + 1)) {
                        ai.hu_deska[x_box][y_box] = 1;
                        pocet_lodi++;
                    }
                    if (pocet_lodi == lode.ctverce) {
                        ai.faze = 2;
                        ai.statusBar.setMessage("Jsi na tahu");
                    }
                }
            }
            if (lod == 2) {
                if (smer % 2 == 1 && x_box == velikost_desky - 1) x_box = velikost_desky - 2;
                if (smer % 2 == 0 && y_box == velikost_desky - 1) y_box = velikost_desky - 2;
                if (y_oldbox != -1 && (x_oldbox != x_box || y_oldbox != y_box || smer_old != smer)) {
                    if (smer_old % 2 == 1) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                    } else {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 1) * 30, 6 + x_oldbox * 30, 29, 29);
                    }
                }
                if (smer % 2 == 1) {
                    if (overitKolizi(x_box - 1, x_box + 2, y_box - 1, y_box + 1)) g.setColor(is_possible);
                    else g.setColor(not_possible);
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 1) * 30, 29, 29);
                    if (put) {
                        if (overitKolizi(x_box - 1, x_box + 2, y_box - 1, y_box + 1)) {
                            ai.hu_deska[x_box][y_box] = 1;
                            ai.hu_deska[x_box + 1][y_box] = 1;
                            pocet_lodi++;
                        }
                    }
                } else {
                    if (overitKolizi(x_box - 1, x_box + 1, y_box - 1, y_box + 2)) g.setColor(is_possible);
                    else g.setColor(not_possible);
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box + 1) * 30, 6 + x_box * 30, 29, 29);
                    if (put) {
                        if (overitKolizi(x_box - 1, x_box + 1, y_box - 1, y_box + 2)) {
                            ai.hu_deska[x_box][y_box] = 1;
                            ai.hu_deska[x_box][y_box + 1] = 1;
                            pocet_lodi++;
                        }
                    }
                }
                if (put) {
                    if (pocet_lodi == lode.obdelniky) {
                        pocet_lodi = 0;
                        lod = 1;
                    }
                }
            }
            if (lod == 3) {
                if (x_box == velikost_desky - 1) x_box = velikost_desky - 2;
                if (smer % 2 == 1 && x_box == 0) x_box = 1;
                if (smer != 3 && y_box == velikost_desky - 1) y_box = velikost_desky - 2;
                if (smer != 1 && y_box == 0) y_box = 1;
                if (y_oldbox != -1 && (x_oldbox != x_box || y_oldbox != y_box || smer_old != smer)) {
                    if (smer_old == 1) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 1) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox - 1) * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                    }
                    if (smer_old == 2) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 1) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                    }
                    if (smer_old == 3) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox - 1) * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                    }
                    if (smer_old == 4) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 1) * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                    }
                }
                if (smer == 1) {
                    if (overitKolizi(x_box - 2, x_box + 2, y_box - 1, y_box + 2, x_box - 2, y_box + 2, x_box + 2, y_box + 2))
                        g.setColor(is_possible);
                    else g.setColor(not_possible);
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box + 1) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box - 1) * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 1) * 30, 29, 29);
                    if (put) {
                        if (overitKolizi(x_box - 2, x_box + 2, y_box - 1, y_box + 2, x_box - 2, y_box + 2, x_box + 2, y_box + 2)) {
                            ai.hu_deska[x_box][y_box] = 1;
                            ai.hu_deska[x_box][y_box + 1] = 1;
                            ai.hu_deska[x_box - 1][y_box] = 1;
                            ai.hu_deska[x_box + 1][y_box] = 1;
                            pocet_lodi++;
                        }
                    }
                }
                if (smer == 2) {
                    if (overitKolizi(x_box - 1, x_box + 2, y_box - 2, y_box + 2, x_box + 2, y_box - 2, x_box + 2, y_box + 2))
                        g.setColor(is_possible);
                    else g.setColor(not_possible);
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box + 1) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 1) * 30, 29, 29);
                    if (put) {
                        if (overitKolizi(x_box - 1, x_box + 2, y_box - 2, y_box + 2, x_box + 2, y_box - 2, x_box + 2, y_box + 2)) {
                            ai.hu_deska[x_box][y_box] = 1;
                            ai.hu_deska[x_box][y_box - 1] = 1;
                            ai.hu_deska[x_box][y_box + 1] = 1;
                            ai.hu_deska[x_box + 1][y_box] = 1;
                            pocet_lodi++;
                        }
                    }
                }
                if (smer == 3) {
                    if (overitKolizi(x_box - 2, x_box + 2, y_box - 2, y_box + 1, x_box - 2, y_box - 2, x_box + 2, y_box - 2))
                        g.setColor(is_possible);
                    else g.setColor(not_possible);
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box - 1) * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 1) * 30, 29, 29);
                    if (put) {
                        if (overitKolizi(x_box - 2, x_box + 2, y_box - 2, y_box + 1, x_box - 2, y_box - 2, x_box + 2, y_box - 2)) {
                            ai.hu_deska[x_box][y_box] = 1;
                            ai.hu_deska[x_box][y_box - 1] = 1;
                            ai.hu_deska[x_box - 1][y_box] = 1;
                            ai.hu_deska[x_box + 1][y_box] = 1;
                            pocet_lodi++;
                        }
                    }
                }
                if (smer == 4) {
                    if (overitKolizi(x_box - 1, x_box + 2, y_box - 2, y_box + 2, x_box - 1, y_box - 2, x_box - 1, y_box + 2))
                        g.setColor(is_possible);
                    else g.setColor(not_possible);
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + (x_box + 1) * 30, 29, 29);
                    g.fillRect(6 + (y_box + 1) * 30, 6 + (x_box + 1) * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 1) * 30, 29, 29);
                    if (put) {
                        if (overitKolizi(x_box - 1, x_box + 2, y_box - 2, y_box + 2, x_box - 1, y_box - 2, x_box - 1, y_box + 2)) {
                            ai.hu_deska[x_box][y_box] = 1;
                            ai.hu_deska[x_box + 1][y_box - 1] = 1;
                            ai.hu_deska[x_box + 1][y_box + 1] = 1;
                            ai.hu_deska[x_box + 1][y_box] = 1;
                            pocet_lodi++;
                        }
                    }
                }
                if (put) {
                    if (pocet_lodi == lode.krizniky) {
                        pocet_lodi = 0;
                        lod = 2;
                    }
                }
            }
            if (lod == 4) {
                if (smer == 2 && x_box == velikost_desky - 1) x_box = velikost_desky - 2;
                if (smer % 2 == 1 && x_box >= velikost_desky - 2) x_box = velikost_desky - 3;
                if (smer != 2 && x_box == 0) x_box = 1;
                if (smer == 1 && y_box == velikost_desky - 1) y_box = velikost_desky - 2;
                if (smer % 2 == 0 && y_box >= velikost_desky - 2) y_box = velikost_desky - 3;
                if (smer != 1 && y_box == 0) y_box = 1;
                if (y_oldbox != -1 && (x_oldbox != x_box || y_oldbox != y_box || smer_old != smer)) {
                    if (smer_old == 1) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox - 1) * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 1) * 30, 6 + (x_oldbox - 1) * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 2) * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 1) * 30, 6 + (x_oldbox + 2) * 30, 29, 29);
                    }
                    if (smer_old == 2) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 1) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 2) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 2) * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                    }
                    if (smer_old == 3) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox - 1) * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + (x_oldbox - 1) * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 1) * 30, 29, 29);
                        g.fillRect(6 + y_oldbox * 30, 6 + (x_oldbox + 2) * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + (x_oldbox + 2) * 30, 29, 29);
                    }
                    if (smer_old == 4) {
                        g.fillRect(6 + y_oldbox * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox - 1) * 30, 6 + (x_oldbox - 1) * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 1) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 2) * 30, 6 + x_oldbox * 30, 29, 29);
                        g.fillRect(6 + (y_oldbox + 2) * 30, 6 + (x_oldbox - 1) * 30, 29, 29);
                    }
                }
                g.setColor(is_possible);
                if (smer == 1) {
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box - 1) * 30, 29, 29);
                    g.fillRect(6 + (y_box + 1) * 30, 6 + (x_box - 1) * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 1) * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 2) * 30, 29, 29);
                    g.fillRect(6 + (y_box + 1) * 30, 6 + (x_box + 2) * 30, 29, 29);
                    if (put) {
                        ai.hu_deska[x_box][y_box] = 1;
                        ai.hu_deska[x_box - 1][y_box] = 1;
                        ai.hu_deska[x_box - 1][y_box + 1] = 1;
                        ai.hu_deska[x_box + 1][y_box] = 1;
                        ai.hu_deska[x_box + 2][y_box] = 1;
                        ai.hu_deska[x_box + 2][y_box + 1] = 1;
                        lod = 3;
                    }
                }
                if (smer == 2) {
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + (x_box + 1) * 30, 29, 29);
                    g.fillRect(6 + (y_box + 1) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box + 2) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box + 2) * 30, 6 + (x_box + 1) * 30, 29, 29);
                    if (put) {
                        ai.hu_deska[x_box][y_box] = 1;
                        ai.hu_deska[x_box][y_box - 1] = 1;
                        ai.hu_deska[x_box + 1][y_box - 1] = 1;
                        ai.hu_deska[x_box][y_box + 1] = 1;
                        ai.hu_deska[x_box][y_box + 2] = 1;
                        ai.hu_deska[x_box + 1][y_box + 2] = 1;
                        lod = 3;
                    }
                }
                if (smer == 3) {
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box - 1) * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + (x_box - 1) * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 1) * 30, 29, 29);
                    g.fillRect(6 + y_box * 30, 6 + (x_box + 2) * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + (x_box + 2) * 30, 29, 29);
                    if (put) {
                        ai.hu_deska[x_box][y_box] = 1;
                        ai.hu_deska[x_box - 1][y_box] = 1;
                        ai.hu_deska[x_box - 1][y_box - 1] = 1;
                        ai.hu_deska[x_box + 1][y_box] = 1;
                        ai.hu_deska[x_box + 2][y_box] = 1;
                        ai.hu_deska[x_box + 2][y_box - 1] = 1;
                        lod = 3;
                    }
                }
                if (smer == 4) {
                    g.fillRect(6 + y_box * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box - 1) * 30, 6 + (x_box - 1) * 30, 29, 29);
                    g.fillRect(6 + (y_box + 1) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box + 2) * 30, 6 + x_box * 30, 29, 29);
                    g.fillRect(6 + (y_box + 2) * 30, 6 + (x_box - 1) * 30, 29, 29);
                    if (put) {
                        ai.hu_deska[x_box][y_box] = 1;
                        ai.hu_deska[x_box][y_box - 1] = 1;
                        ai.hu_deska[x_box - 1][y_box - 1] = 1;
                        ai.hu_deska[x_box][y_box + 1] = 1;
                        ai.hu_deska[x_box][y_box + 2] = 1;
                        ai.hu_deska[x_box - 1][y_box + 2] = 1;
                        lod = 3;
                    }
                }
            }
            put = false;
            x_oldbox = x_box; y_oldbox = y_box; smer_old = smer;
        }
    }

    public boolean overitKolizi(int y1, int y2, int x1, int x2) {
        return overitKolizi(y1, y2, x1, x2, -1, -1, -1, -1);
    }

    public boolean overitKolizi(int y1, int y2, int x1, int x2, int vynechej_y1, int vynechej_x1, int vynechej_y2, int vynechej_x2) {
        for (int i = y1; i <= y2; i++) {
            if (i < 0 || i >= velikost_desky) continue;
            for (int j = x1; j <= x2; j++) {
                if (j < 0 || j >= velikost_desky) continue;
                if (i == vynechej_y1 && j == vynechej_x1 || i == vynechej_y2 && j == vynechej_x2) continue;
                if (ai.hu_deska[i][j] == 1) return false;
            }
        }
        return true;
    }
}

class StatusBar extends JLabel {
    public StatusBar() {
        super();
        super.setPreferredSize(new Dimension(100, 16));
    }

    public void setMessage(String message) {
        setText(" " + message);
        super.setAlignmentY(BOTTOM_ALIGNMENT);
    }
}
