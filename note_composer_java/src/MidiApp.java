package midiapp;

import javax.swing.JFrame;


public class MidiApp extends JFrame {
    DrawingPanel dp = new DrawingPanel();

    public static void main(String[] args) {
        JFrame f = new SwingApp();
        f.show();
    }
}
