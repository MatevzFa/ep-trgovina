package ep.rest;

import android.content.Context;
import android.support.annotation.NonNull;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.Locale;

public class IzdelekAdapter extends ArrayAdapter<Izdelek> {
    public IzdelekAdapter(Context context) {
        super(context, 0, new ArrayList<Izdelek>());
    }

    @Override
    public View getView(int position, View convertView, @NonNull ViewGroup parent) {
        final Izdelek izdelek = getItem(position);

        // Check if an existing view is being reused, otherwise inflate the view
        if (convertView == null) {
            convertView = LayoutInflater.from(getContext()).inflate(R.layout.booklist_element, parent, false);
        }

        final TextView tvTitle = (TextView) convertView.findViewById(R.id.tv_title);
        final TextView tvPrice = (TextView) convertView.findViewById(R.id.tv_price);

        tvTitle.setText(izdelek.ime);
        tvPrice.setText(String.format(Locale.ENGLISH, "%.2f EUR", izdelek.cena));

        return convertView;
    }
}
