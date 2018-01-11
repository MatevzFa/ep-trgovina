package ep.rest;

import java.io.Serializable;
import java.util.Locale;

public class Izdelek implements Serializable {
    public int id, year=0;
    public String author = "", ime, uri = "", opis;
    public double cena;

    @Override
    public String toString() {
        return String.format(Locale.ENGLISH,
                "%s: %s, %d (%.2f EUR)",
                author, ime, year, cena);
    }
}
