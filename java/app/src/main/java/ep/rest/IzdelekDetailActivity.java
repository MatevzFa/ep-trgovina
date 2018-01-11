package ep.rest;

import android.app.AlertDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.CollapsingToolbarLayout;
import android.support.design.widget.FloatingActionButton;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.View;
import android.widget.TextView;

import java.io.IOException;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class IzdelekDetailActivity extends AppCompatActivity implements Callback<Izdelek> {
    private static final String TAG = IzdelekDetailActivity.class.getCanonicalName();

    private Izdelek izdelek;
    private TextView tvBookDetail;
    private CollapsingToolbarLayout toolbarLayout;
    private FloatingActionButton fabEdit, fabDelete;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_book_detail);
        final Toolbar toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        toolbarLayout = (CollapsingToolbarLayout) findViewById(R.id.toolbar_layout);

        tvBookDetail = (TextView) findViewById(R.id.tv_book_detail);

        fabEdit = (FloatingActionButton) findViewById(R.id.fab_edit);
        fabEdit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                final Intent intent = new Intent(IzdelekDetailActivity.this, IzdelekFormActivity.class);
                intent.putExtra("ep.rest.izdelek", izdelek);
                startActivity(intent);
            }
        });
        fabDelete = (FloatingActionButton) findViewById(R.id.fab_delete);
        fabDelete.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                final AlertDialog.Builder dialog = new AlertDialog.Builder(IzdelekDetailActivity.this);
                dialog.setTitle("Confirm deletion");
                dialog.setMessage("Are you sure?");
                dialog.setPositiveButton("Yes", new DialogInterface.OnClickListener() {
                    @Override
                    public void onClick(DialogInterface dialog, int which) {
                        deleteBook();
                    }
                });
                dialog.setNegativeButton("Cancel", null);
                dialog.create().show();
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
            tvBookDetail.setText(izdelek.opis);
            toolbarLayout.setTitle(izdelek.ime);
        } else {
            String errorMessage;
            try {
                errorMessage = "An error occurred: " + response.errorBody().string();
            } catch (IOException e) {
                errorMessage = "An error occurred: error while decoding the error message.";
            }
            Log.e(TAG, errorMessage);
            tvBookDetail.setText(errorMessage);
        }
    }

    @Override
    public void onFailure(Call<Izdelek> call, Throwable t) {
        Log.w(TAG, "Error: " + t.getMessage(), t);
    }
}
