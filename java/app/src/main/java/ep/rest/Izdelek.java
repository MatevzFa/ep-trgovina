package ep.rest;

import java.io.Serializable;
import java.util.List;
import java.util.Locale;

public class Izdelek implements Serializable {

    public int id;
    public String ime, opis;
    public double cena;
    public double povprecnaOcena;
    public List<Slika> slike;

    @Override
    public String toString() {
        return String.format(Locale.ENGLISH,
                "%s: %s (%.2f EUR)",
                ime, opis, cena) + slike.toString();
    }

    public String ocenaString() {
        if (this.povprecnaOcena == 0) {
            return "N/A";
        } else {
            return Double.toString(povprecnaOcena);
        }
    }
}
