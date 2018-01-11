package ep.rest;

import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.CollapsingToolbarLayout;
import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import java.io.IOException;

import okhttp3.Headers;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class IzdelekDetailActivity extends AppCompatActivity implements Callback<Izdelek> {
    private static final String TAG = IzdelekDetailActivity.class.getCanonicalName();

    private Izdelek izdelek;
    private TextView tvIzdelekOpis;
    private TextView tvIzdelekCena;
    private TextView tvIzdelekOcena;
    private CollapsingToolbarLayout toolbarLayout;
    private FloatingActionButton fabEdit;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_book_detail);
        final Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        toolbarLayout = (CollapsingToolbarLayout) findViewById(R.id.toolbar_layout);

        tvIzdelekOpis = (TextView) findViewById(R.id.izdelek_opis);
        tvIzdelekCena = (TextView) findViewById(R.id.izdelek_cena);
        tvIzdelekOcena = (TextView) findViewById(R.id.izdelek_ocena);

        fabEdit = (FloatingActionButton) findViewById(R.id.fab_edit);
        fabEdit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                final Intent intent = new Intent(IzdelekDetailActivity.this, IzdelekFormActivity.class);
                intent.putExtra("ep.rest.izdelek", izdelek);
                startActivity(intent);
            }
        });


        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        final int id = getIntent().getIntExtra("ep.rest.id", 0);
        if (id > 0) {
            TrgovinaService.getInstance().get(id).enqueue(this);
        }
    }

    private void deleteBook() {
        // todo
    }

    @Override
    public void onResponse(Call<Izdelek> call, Response<Izdelek> response) {
        izdelek = response.body();
        Log.i(TAG, "Got result: " + izdelek);

        if (response.isSuccessful()) {

            Headers headers = response.headers();
            Log.i(TAG, headers.toString());

            tvIzdelekOpis.setText(izdelek.opis);
            tvIzdelekCena.setText(Double.toString(izdelek.cena) + " EUR");
            tvIzdelekOcena.setText(izdelek.ocenaString());
            toolbarLayout.setTitle(izdelek.ime);

            LinearLayout slike = findViewById(R.id.slike);

            for (Slika s : izdelek.slike) {
                ImageView img = new ImageView(this);


                new DownloadImageTask(img).execute(s.path);

                img.setAdjustViewBounds(true);
                img.setMaxHeight(200);

                slike.addView(img);
            }

        } else {
            String errorMessage;
            try {
                errorMessage = "An error occurred: " + response.errorBody().string();
            } catch (IOException e) {
                errorMessage = "An error occurred: error while decoding the error message.";
            }
            Log.e(TAG, errorMessage);
            tvIzdelekOpis.setText(errorMessage);
        }
    }

    @Override
    public void onFailure(Call<Izdelek> call, Throwable t) {
        Log.w(TAG, "Error: " + t.getMessage(), t);
    }


}
