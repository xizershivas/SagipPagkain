using Microsoft.EntityFrameworkCore;
using SagipPagkain.API.Models;

namespace SagipPagkain.API.Data;

public class AppDbContext(DbContextOptions<AppDbContext> options) : DbContext(options)
{
    public DbSet<User> Users { get; set; }
    public DbSet<FoodBank> FoodBanks { get; set; }
    public DbSet<FoodBankDetail> FoodBankDetails { get; set; }
    public DbSet<Category> Categories { get; set; }
    public DbSet<Unit> Units { get; set; }
    public DbSet<Item> Items { get; set; }
    public DbSet<Purpose> Purposes { get; set; }
    public DbSet<Donation> Donations { get; set; }
    public DbSet<Inventory> Inventories { get; set; }
    public DbSet<Beneficiary> Beneficiaries { get; set; }
    public DbSet<BeneficiaryRequest> BeneficiaryRequests { get; set; }
    public DbSet<BeneficiaryRequestDetail> BeneficiaryRequestDetails { get; set; }
    public DbSet<Volunteer> Volunteers { get; set; }
    public DbSet<Notification> Notifications { get; set; }

    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        base.OnModelCreating(modelBuilder);

        modelBuilder.Entity<User>()
            .HasOne(u => u.FoodBank)
            .WithMany(f => f.Users)
            .HasForeignKey(u => u.intFoodBankId)
            .IsRequired(false);

        modelBuilder.Entity<User>()
            .HasOne(u => u.Beneficiary)
            .WithOne(b => b.User)
            .HasForeignKey<Beneficiary>(b => b.intUserId);

        modelBuilder.Entity<FoodBankDetail>()
            .HasOne(fd => fd.FoodBank)
            .WithMany(f => f.FoodBankDetails)
            .HasForeignKey(fd => fd.intFoodBankId);

        modelBuilder.Entity<Item>()
            .HasOne(i => i.Category)
            .WithMany(c => c.Items)
            .HasForeignKey(i => i.intCategoryId);

        modelBuilder.Entity<Item>()
            .HasOne(i => i.Unit)
            .WithMany(u => u.Items)
            .HasForeignKey(i => i.intUnitId);

        modelBuilder.Entity<Donation>()
            .HasOne(d => d.User)
            .WithMany()
            .HasForeignKey(d => d.intUserId);

        modelBuilder.Entity<Donation>()
            .HasOne(d => d.FoodBankDetail)
            .WithMany(fd => fd.Donations)
            .HasForeignKey(d => d.intFoodBankDetailId);

        modelBuilder.Entity<Donation>()
            .HasOne(d => d.Inventory)
            .WithOne(i => i.Donation)
            .HasForeignKey<Inventory>(i => i.intDonationId);

        modelBuilder.Entity<Inventory>()
            .HasOne(i => i.FoodBankDetail)
            .WithMany(fd => fd.Inventories)
            .HasForeignKey(i => i.intFoodBankDetailId);

        modelBuilder.Entity<Inventory>()
            .HasOne(i => i.Item)
            .WithMany()
            .HasForeignKey(i => i.intItemId);

        modelBuilder.Entity<Inventory>()
            .HasOne(i => i.Category)
            .WithMany()
            .HasForeignKey(i => i.intCategoryId);

        modelBuilder.Entity<Inventory>()
            .HasOne(i => i.Unit)
            .WithMany()
            .HasForeignKey(i => i.intUnitId);

        modelBuilder.Entity<BeneficiaryRequest>()
            .HasOne(r => r.Beneficiary)
            .WithMany(b => b.BeneficiaryRequests)
            .HasForeignKey(r => r.intBeneficiaryId);

        modelBuilder.Entity<BeneficiaryRequest>()
            .HasOne(r => r.FoodBankDetail)
            .WithMany()
            .HasForeignKey(r => r.intFoodBankDetailId);

        modelBuilder.Entity<BeneficiaryRequestDetail>()
            .HasOne(rd => rd.BeneficiaryRequest)
            .WithMany(r => r.RequestDetails)
            .HasForeignKey(rd => rd.intBeneficiaryRequestId);

        modelBuilder.Entity<BeneficiaryRequestDetail>()
            .HasOne(rd => rd.Item)
            .WithMany()
            .HasForeignKey(rd => rd.intItemId);
    }
}
