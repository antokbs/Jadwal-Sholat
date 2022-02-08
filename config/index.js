function cmdSave_onClick() {
  if (confirm("Data Disimpan ?")) {
    ajax("", "SaveData", GetFormContent(), function (cData) {
      alert(cData);
    });
  }
}