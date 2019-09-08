package midiapp;

import java.awt.*;
import javax.swing.*;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Iterator;
import javax.imageio.ImageIO;

/**
 *
 * @author Kuba
 */
public class DrawingPanel extends JPanel {
    private BufferedImage clef;
    public int[] notePos = {5, 16, 26, 36, 46, 58, 68, 78, 88, 100, 111, 121, 132, 142, 153, 163}; //y-ové polohy not
    public int[] halfNotePos = {80, 77, 87, 50, 43, 66};
    public BufferedImage[] noteImgs = new BufferedImage[18];  //jednotlivé obrázky na vykreslovací plochu
    public int noteCount = 0;
    public ArrayList<Notes> notes = new ArrayList<Notes>();
    public byte noteType = 2;
    public byte halfNote = 0;
    public boolean dotted = false;
    public int row = 0;
    public int list = 0;

    public void ImagePanel() {
        try {
            clef = ImageIO.read(new File("images/key.png"));
            noteImgs[0] = ImageIO.read(new File("images/wholenote.gif"));
            noteImgs[1] = ImageIO.read(new File("images/halfnote.gif"));
            noteImgs[2] = ImageIO.read(new File("images/quarternote.gif"));
            noteImgs[3] = ImageIO.read(new File("images/eighthnote.gif"));
            noteImgs[4] = ImageIO.read(new File("images/sixteenthnote.gif"));
            noteImgs[5] = ImageIO.read(new File("images/wholerest.gif"));
            noteImgs[6] = ImageIO.read(new File("images/halfrest.gif"));
            noteImgs[7] = ImageIO.read(new File("images/quarterrest.gif"));
            noteImgs[8] = ImageIO.read(new File("images/eighthrest.gif"));
            noteImgs[9] = ImageIO.read(new File("images/sixteenthrest.gif"));
            noteImgs[10] = ImageIO.read(new File("images/halfnote-updown.gif"));
            noteImgs[11] = ImageIO.read(new File("images/quarternote-updown.gif"));
            noteImgs[12] = ImageIO.read(new File("images/eighthnote-updown.gif"));
            noteImgs[13] = ImageIO.read(new File("images/sixteenthnote-updown.gif"));
            noteImgs[14] = ImageIO.read(new File("images/flat.gif"));
            noteImgs[15] = ImageIO.read(new File("images/natural.gif"));
            noteImgs[16] = ImageIO.read(new File("images/sharp.gif"));
            noteImgs[17] = ImageIO.read(new File("images/dot.gif"));
        } catch (IOException ex) {
            //Logger.getLogger(DrawingPanel.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    private void drawRows(Graphics g) {
        g.setColor(Color.black);
        //k notovému klíči nakreslíme linky, pátá linka je mírně odskočená, tak ji ještě o pixel posuneme
        //for (int k = 0; k <= noteCount / 16; k++) {
        for (int k = 0; k <= 2; k++) {
            g.drawImage(clef, 5, 85 + k * 165, this);
            for (int i = 0; i < 5; i++) {
                g.drawLine(69, 85 + 165 * k + 27 + i * 21 + (int)(i / 4), 1003, 85 + 165 * k + 27 + i * 21 + (int)(i / 4));
            }
        }
    }

    public void drawNotes(Graphics g) {
        Iterator itr = notes.iterator();
        int i = -1;
        while (itr.hasNext()) {
            Notes n = (Notes) itr.next();

            //chceme jen určitý výřez
            i++;
            if (i < list * 48)
                continue;
            if (i >= list * 48 + 48)
                break;

            //převrácená nota
            if (n.getType() >= 1 && n.getType() <= 4 && n.getYPos() <= 6)
                g.drawImage(noteImgs[n.getType() + 9], 90 + n.getXPos() * 57, notePos[n.getYPos()] + 65 + 165 * n.getRow(), this);
            //nota běžně shora dolů
            else g.drawImage(noteImgs[n.getType()], 90 + n.getXPos() * 57, notePos[n.getYPos()] + 165 * n.getRow(), this);

            //pomocná čára
            if (n.getYPos() == 0 || n.getYPos() >= 12)
                g.drawLine(90 + n.getXPos() * 57 + 5, notePos[(int)(n.getYPos() / 2) * 2] + 87 + 165 * n.getRow(),
                           90 + n.getXPos() * 57 + 55, notePos[(int)(n.getYPos() / 2) * 2] + 87 + 165 * n.getRow());
            if (n.getYPos() == 14)
                g.drawLine(90 + n.getXPos() * 57 + 5, notePos[12] + 87 + 165 * n.getRow(),
                           90 + n.getXPos() * 57 + 55, notePos[12] + 87 + 165 * n.getRow());

            //půltóny
            if (n.getHalfNote() > 0)
                g.drawImage(noteImgs[n.getHalfNote() + 13], halfNotePos[n.getHalfNote() - 1] + n.getXPos() * 57,
                            notePos[n.getYPos()] + halfNotePos[n.getHalfNote() + 2] + 165 * n.getRow(), this);

            //tečky
            if (n.getDotted())
                g.drawImage(noteImgs[17], 140 + n.getXPos() * 57, notePos[n.getYPos()] + 83 + 165 * n.getRow(), this);

            //konec taktu
            //currentM += 1 / Math.pow(2, n.getType());
            /*
            if (n.getBarEnd()) {
                g.drawLine(149 + n.getXPos() * 57, 112 + 165 * n.getRow(), 149 + n.getXPos() * 57, 197 + 165 * n.getRow());
                g.drawLine(150 + n.getXPos() * 57, 112 + 165 * n.getRow(), 150 + n.getXPos() * 57, 197 + 165 * n.getRow());
            }
            */
        }
    }

    public void drawPages(Graphics g) {
        g.setFont(new Font("Dialog", Font.BOLD, 18));
        g.drawString((list + 1) + "/" + (noteCount / 48 + 1), 950, 580);
    }

    @Override
    public void paintComponent(Graphics g) {
        super.paintComponent(g);

        g.setColor(Color.gray);
        g.drawRect(2, 80, 1002, 515);
        g.setColor(Color.white);
        g.fillRect(3, 81, 1001, 514);

        drawRows(g);
        drawNotes(g);
        drawPages(g);
    }
}
