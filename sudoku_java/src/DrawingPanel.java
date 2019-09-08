/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package sudoku;

import java.awt.*;
import java.io.Serializable;
import javax.swing.*;

/**
 *
 * @author Kuba
 */
public class DrawingPanel extends JPanel implements Serializable {

    public int hraci_deska[][] = new int[9][9];
    public int vyresena_deska[][] = new int[9][9];
    public int mnozina[] = new int[81];
    public int mnozina_sort[][] = new int[81][2];
    public int pole[][] = new int[81][10];
    public int vybrane_cislo = 1;
    public int difficulty = 1;
    
    public SaveGrid getSerGrid() {
        SaveGrid sg = new SaveGrid();
        sg.save_hraci_deska = hraci_deska;
        sg.save_vyresena_deska = vyresena_deska;
        return sg; 
    }
    
    public void setSerGrid(SaveGrid sg) {
        hraci_deska = sg.save_hraci_deska;
        vyresena_deska = sg.save_vyresena_deska;
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);
        g.setColor(Color.blue);

        for (int i = 5; i <= 365; i += 40) {
            if (i == 5 || i == 125 || i == 245 || i == 365) {
                g.drawLine(i - 1, 45, i - 1, 405);
                g.drawLine(i + 1, 45, i + 1, 405);
            }
            g.drawLine(i, 45, i, 405);
        }
        for (int i = 45; i <= 405; i += 40) {
            if (i == 45 || i == 165 || i == 285 || i == 405) {
                g.drawLine(5, i - 1, 365, i - 1);
                g.drawLine(5, i + 1, 365, i + 1);
            }
            g.drawLine(5, i, 365, i);
            if (i != 405) {
                if (vybrane_cislo == (i - 45) / 40 + 1) {
                    g.setColor(Color.red);
                }
                g.drawRect(375, i, 40, 40);
                g.drawRect(376, i + 1, 38, 38);
                g.setColor(Color.blue);
            }
        }

        g.setFont(new Font("Helvetica", Font.BOLD, 24));
        for (int i = 0; i < 9; i++) {
            for (int j = 0; j < 9; j++) {
                if (hraci_deska[i][j] != 0) {
                    g.drawString(Integer.toString(hraci_deska[i][j]), 20 + 40 * j, 75 + 40 * i);
                }
            }
            g.drawString(Integer.toString(i + 1), 390, 75 + 40 * i);
        }
    }
}
